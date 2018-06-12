<?php

	defined('_DIRECT_ACCESS_CHECK') or exit();

	$bcrypt_cost = 11;

	$static_key = base64_decode('tGOrlqr/97qxQwby+uwsbReOLxTLgrMntDKX/Uj4LMy6YSSQ9Xr4DjMgKVhnUT2pZ/YFJUo/qE/xBMD5dZBF9ZBZTnPNz+Pnez1OazpoEAy2M3vE7N/kQ4tP7kA98jf+NCKqi8MLJ6hQPMPXFuciIQsKUNWc6clJ+q5GwJAxikyxy8VCnDgOEoV0u6GpVB7syrB1OlvORAWsB7wqkq2XmiZnRVJsnz/td6kBhnwPE5F7ghlncZcyXjuL86M/bFlrqtm6pH6mVLWIAeRFdH7jVdgB/ihVsnaxXXkHnY9AZEzmuk19r+IiRHIv+ft299t//ddFM5lGduwqKCJ8tfpWFQ==');

	function encrypt_decrypt($encrypt, $key, $iv, $string) {
		if( $encrypt == true) {
			return openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);
		} else {
			return openssl_decrypt($string, 'AES-256-CBC', $key, 0, $iv);
		}
	}

	function connect($databaseName) {
		$db = new PDO("sqlite:{$databaseName}");
		$db->exec('CREATE TABLE IF NOT EXISTS "secrets" ("hash" TEXT PRIMARY KEY, "iv" TEXT, "secret" TEXT)');
		$db->exec('CREATE TABLE IF NOT EXISTS "salts" ("id" TEXT PRIMARY KEY, "salt" TEXT)');
		return $db;
	}

	function writeSecret($db, $hash, $iv, $secret) {
		$statement = $db->prepare('INSERT INTO "secrets" ("hash", "iv", "secret") VALUES (:hash, :iv, :secret)');
		$statement->bindValue(':hash', $hash);
		$statement->bindValue(':iv', $iv);
		$statement->bindValue(':secret', $secret);
		$statement->execute();
	}

	function writeSalt($db, $saltid, $salt) {
		$statement = $db->prepare('INSERT INTO "salts" ("id", "salt") VALUES (:id, :salt)');
		$statement->bindValue(':id', $saltid);
		$statement->bindValue(':salt', $salt);
		$statement->execute();
	}

	function readSecret($db, $hash) {
		$statement = $db->prepare('SELECT * FROM "secrets" WHERE hash = :hash LIMIT 1');
		$statement->bindValue(':hash', $hash);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	function readSalt($db, $saltid) {
		$statement = $db->prepare('SELECT * FROM "salts" WHERE id = :saltid LIMIT 1');
		$statement->bindValue(':saltid', $saltid);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	function deleteSecret($db, $hash) {
		$statement = $db->prepare('DELETE FROM "secrets" WHERE hash = :hash');
		$statement->bindValue(':hash', $hash);
		$statement->execute();
	}

	function deleteSalt($db, $saltid) {
		$statement = $db->prepare('DELETE FROM "salts" WHERE id = :saltid');
		$statement->bindValue(':saltid', $saltid);
		$statement->execute();
	}

	function random_str($len) {
		for ($i = -1; $i <= $len; $i++) {
			$bytes = openssl_random_pseudo_bytes($i, $cstrong);
		}
		return $bytes;
	}

	function base64_encode_mod($input) {
		return strtr(base64_encode($input), '+/=', '-_$');
	}

	function base64_decode_mod($input) {
		return base64_decode(strtr($input, '-_$', '+/='));
	}

	function store_secret($secret) {
		global $bcrypt_cost, $static_key;

		#connect to sqlite db
		$db = connect('secrets.sqlite');

		#generate random key, iv, salt, and saltid
		$key = random_str(32);
		$iv = random_str(16);
		$salt = random_str(64);
		$saltid = random_str(16);

		#generate hash of key
		$secret_hash = password_hash($key, PASSWORD_BCRYPT, ['cost' => $bcrypt_cost, 'salt' => $salt]);

		#encrypt text with key and then static key
		$secret = encrypt_decrypt(true, $key, $iv, $secret);
		$secret = encrypt_decrypt(true, $static_key, $iv, $secret);

		#generate k value for url
		$k = base64_encode_mod($saltid . $key);

		#base64 encode the secret_hash, iv, salt, saltid, and secret for db storage
		$secret_hash = base64_encode_mod($secret_hash);
		$iv = base64_encode_mod($iv);
		$salt = base64_encode_mod($salt);
		$saltid = base64_encode_mod($saltid);
		$secret = base64_encode_mod($secret);

		#write secret_hash, iv, and secret to database
		writeSecret($db, $secret_hash, $iv, $secret);

		#write saltid and salt to database
		writeSalt($db, $saltid, $salt);

		#close db
		$db = null;

		#base64 encode the saltID + key (for URL)
		return $k;
	}

	function retrieve_secret($k) {
		global $bcrypt_cost, $static_key;

		#connect to sqlite db
		$db = connect('secrets.sqlite');

		#validate length of k - must be 48 chars (saltid = 16, key = 32)
		if ( strlen(base64_decode_mod($k)) != 48 ) {
			throw new Exception('This secret can not be found!');
		}

		#base64 decode k
		$k = base64_decode_mod($k);

		#extract saltid from k and base64 encode it
		$saltid = substr($k, 0, 16);
		$saltid = base64_encode_mod($saltid);

		#look up salt with saltid
		$saltResult = readSalt($db, $saltid);

		#throw exception if query failed
		if ( ! $saltResult ) {
			throw new Exception('This secret can not be found!');
		}

		#get salt from query results and base64 decode it
		$salt = $saltResult['salt'];
		$salt = base64_decode_mod($salt);

		#extract key from k
		$key = substr($k, -32);

		#generate hash of iv + key & base64 encode it
		$secret_hash = password_hash($key, PASSWORD_BCRYPT, ['cost' => $bcrypt_cost, 'salt' => $salt]);
		$secret_hash = base64_encode_mod($secret_hash);

		#read secret, hash, and iv from db
		$db_result = readSecret($db, $secret_hash);

		#throw exception if query failed
		if ( ! $db_result ) {
			throw new Exception('This secret can not be found!');
		}

		#get iv from query results and base64 decode it
		$iv = $db_result['iv'];
		$iv = base64_decode_mod($iv);

		#get secret from query results and base64 decode it
		$secret = $db_result['secret'];
		$secret = base64_decode_mod($secret);

		#decrypt secret with the static key, and then with url key
		$secret = encrypt_decrypt(false, $static_key, $iv, $secret);
		$secret = encrypt_decrypt(false, $key, $iv, $secret);

		#delete secret and salt from db
		deleteSecret($db, $secret_hash);
		deleteSalt($db, $saltid);

		#close db
		$db = null;

		#return decrypted text
		return $secret;
	}

?>
