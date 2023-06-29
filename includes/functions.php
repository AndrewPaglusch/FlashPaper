<?php

	defined('_DIRECT_ACCESS_CHECK') or exit();

	function encrypt_decrypt($encrypt, $key, $iv, $string) {
		if ( $encrypt == true ) {
			$encText = openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);
			if ( ! $encText ) {
				throw new Exception('Failed to encrypt data!');
			} else {
				return $encText;
			}
		} else {
			$decText = openssl_decrypt($string, 'AES-256-CBC', $key, 0, $iv);
			if ( ! $decText ) {
				throw new Exception('Failed to decrypt data!');
			} else {
				return $decText;
			}
		}
	}

	function connect() {
		$dbName = "secrets.sqlite";
		$results = glob("./data/*--{$dbName}");

		# find name of existing db or generate a new one if not found
		if ( count($results) != 1 ) {
			$prefix = crypto_rand_string(32);
			$dbName = "./data/{$prefix}--{$dbName}";
		} else {
			$dbName = $results[0];
		}

		# open the db (create if it doesnt exist)
		try {
			$db = new PDO("sqlite:{$dbName}");
			$db->exec('CREATE TABLE IF NOT EXISTS "secrets" ("id" TEXT PRIMARY KEY, "iv" TEXT, "hash" TEXT, "secret" TEXT, "views" INTEGER, "views_max" INTEGER, "prune_epoch" INTEGER)');
			return $db;
		} catch (Exception $e) {
			# re-throw exception so we can catch it higer up with a more helpful error message
			throw new Exception('Failed to create or open database!');
		}
	}

	function getStaticKey() {
		$keyName = "aes-static.key";
		$results = glob("./data/*--{$keyName}");
		$staticKey = null;

		if ( count($results) != 1 ) {
			#static key needs to be created
			$prefix = crypto_rand_string(32);
			$keyName = "./data/{$prefix}--{$keyName}";
			$staticKey = random_bytes(32);

			if ( $fp = fopen($keyName, "w") ) {
				fwrite($fp, $staticKey);
				fclose($fp);
			} else {
				throw new Exception('Failed to write static key to disk!');
			}
		} else {
			#read static key from disk
			$keyName = $results[0];
			if ( ($fp = fopen($keyName, "rb")) !== false ) {
				$staticKey = stream_get_contents($fp);
				fclose($fp);
			} else {
				throw new Exception('Unable to read static key from disk!');
			}
		}

		if ( strlen($staticKey) >= 32 ) {
			return $staticKey;
		} else {
			throw new Exception('Bad static key length!');
		}
	}

	function writeSecret($db, $id, $iv, $hash, $secret, $views, $views_max, $prune_epoch) {
		$statement = $db->prepare('INSERT INTO "secrets" ("id", "iv", "hash", "secret", "views", "views_max", "prune_epoch") VALUES (:id, :iv, :hash, :secret, :views, :views_max, :prune_epoch)');
		$statement->bindValue(':id', $id);
		$statement->bindValue(':iv', $iv);
		$statement->bindValue(':hash', $hash);
		$statement->bindValue(':secret', $secret);
		$statement->bindValue(':views', $views);
		$statement->bindValue(':views_max', $views_max);
		$statement->bindValue(':prune_epoch', $prune_epoch);
		if ( ! $statement->execute() ) {
			throw new Exception('Failed to write to database!');
		}
	}

	function readSecret($db, $id) {
		$statement = $db->prepare('SELECT * FROM "secrets" WHERE id = :id LIMIT 1');
		$statement->bindValue(':id', $id);
		if ( ! $statement->execute() ) {
			throw new Exception('Failed to read from database!');
		} else {
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
	}

	function updateViews($db, $id, $views) {
		$statement = $db->prepare('UPDATE "secrets" SET views=:views WHERE id = :id');
		$statement->bindValue(':id', $id);
		$statement->bindValue(':views', $views);
		if ( ! $statement->execute() ) {
			throw new Exception('Failed to read from database!');
		} 
	}

	function deleteSecret($db, $id) {
		$db->exec('PRAGMA secure_delete = 1');
		$statement = $db->prepare('DELETE FROM "secrets" WHERE id = :id');
		$statement->bindValue(':id', $id);
		if ( ! $statement->execute() ) {
			throw new Exception('Failed to write to database!');
		}

		$verify = $db->prepare('SELECT COUNT(*) FROM "secrets" WHERE id = :id');
		$verify->bindValue(':id', $id);
		if ( ! $verify->execute() ) {
			throw new Exception('Failed to read from database!');
		} else {
			return ( $verify->fetchColumn() == 0 );
		}
	}

	function secretCleanup($db) {
		$db->exec('PRAGMA secure_delete = 1');
		$statement = $db->prepare('DELETE FROM "secrets" WHERE prune_epoch < :epoch_now');
		$statement->bindValue(':epoch_now', time());
		if ( ! $statement->execute() ) {
			throw new Exception('Failed to purge secrets from database!');
		}
	}

	function crypto_rand_string($strLen) {
		# random_int() generates cryptographically secure pseudo-random integers
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$key = '';
		for ($i = 0; $i < $strLen; ++$i) {
			$key .= $chars[random_int(0, strlen($chars) -1)];
		}
		return $key;
	}

	function store_secret($secret, $settings, $expire_days, $views_max) {
		#connect to sqlite db
		$db = connect();

		#generate random id, iv, key
		$id = crypto_rand_string(8);
		$iv = crypto_rand_string(16);
		$key = crypto_rand_string(32);

		#generate expiration datetime
		$min_days = $settings['prune']['min_days'];
		$max_days = $settings['prune']['max_days'];
		$prune_epoch = time() + (86400 * $expire_days);

		#generate k value for url (id + key)
		$k = $id . $key;

		#generate hash of id + key
		$hash = password_hash($id . $key, PASSWORD_BCRYPT);

		#encrypt text with key and then static key
		$secret = encrypt_decrypt(true, $key, $iv, $secret);
		$secret = encrypt_decrypt(true, getStaticKey(), $iv, $secret);

		$views = 0;

		$views_max = (int)$views_max;

		#write id, iv, bcrypt password hash, and secret to database
		writeSecret($db, $id, $iv, $hash, $secret, $views, $views_max, $prune_epoch);

		#close db
		$db = null;

		return $k;
	}

	function retrieve_secret($k) {

		#connect to sqlite db
		$db = connect();

		#validate length of k - must be 40 chars (id = 8, key = 32)
		if ( strlen($k) != 40 ) {
			throw new Exception('This secret can not be found!');
		}

		#extract key and id from k
		$key = substr($k, -32);
		$id = substr($k, 0, 8);

		#validate id before using in db lookup
		if ( preg_match('/[a-z0-9]{8}/i', $id) !== 1 ) {
			throw new Exception('This secret can not be found!');
		}

		#look up secret by id
		$secretQuery = readSecret($db, $id);

		#throw exception if query failed
		if ( ! $secretQuery ) {
			throw new Exception('This secret can not be found!');
		}

		$iv = $secretQuery['iv'];
		$hash = $secretQuery['hash'];
		$secret = $secretQuery['secret'];
		$views = $secretQuery['views'];
		$views_max = $secretQuery['views_max'];
		$views_left = $views_max - $views+1;

		#verify hash from DB equals hash of id + key from URL
		if ( ! password_verify($id . $key, $hash) ) {
			throw new Exception('This secret can not be found!');
		}

		#decrypt secret with the static key, and then with url key
		$secret = encrypt_decrypt(false, getStaticKey(), $iv, $secret);
		$secret = encrypt_decrypt(false, $key, $iv, $secret);
		

		if ( $views + 1 >= $views_max) {
			#delete secret and verify it's gone
			if ( ! deleteSecret($db, $id) ) {
				# if we cant destroy it, dont give the secret out
				throw new Exception('Failed to destroy secret!');
			}
			return array($secret, 0);
		} else {
			updateViews($db, $id, $views+1);
			

			#close db
			$db = null;
			#return decrypted text
			return array($secret, $views_left);
		}
	}

?>
