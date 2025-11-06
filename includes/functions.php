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
			$db->exec('CREATE TABLE IF NOT EXISTS "secrets" ("id" TEXT PRIMARY KEY, "iv" TEXT, "hash" TEXT, "secret" TEXT, "prune_epoch" INTEGER)');
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

	function writeSecret($db, $id, $iv, $hash, $secret, $prune_epoch) {
		$statement = $db->prepare('INSERT INTO "secrets" ("id", "iv", "hash", "secret", "prune_epoch") VALUES (:id, :iv, :hash, :secret, :prune_epoch)');
		$statement->bindValue(':id', $id);
		$statement->bindValue(':iv', $iv);
		$statement->bindValue(':hash', $hash);
		$statement->bindValue(':secret', $secret);
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

	function store_secret($secret, $settings) {
		#connect to sqlite db
		$db = connect();

		#generate random id, iv, key
		$id = crypto_rand_string(8);
		$iv = crypto_rand_string(16);
		$key = crypto_rand_string(32);

		#generate expiration datetime
		$min_days = $settings['prune']['min_days'];
		$max_days = $settings['prune']['max_days'];
		$prune_epoch = rand(time() + (86400 * $min_days), time() + (86400 * $max_days));

		#generate k value for url (id + key)
		$k = $id . $key;

		#generate hash of id + key
		$hash = password_hash($id . $key, PASSWORD_BCRYPT);

		#encrypt text with key and then static key
		$secret = encrypt_decrypt(true, $key, $iv, $secret);
		$secret = encrypt_decrypt(true, getStaticKey(), $iv, $secret);

		#write id, iv, bcrypt password hash, and secret to database
		writeSecret($db, $id, $iv, $hash, $secret, $prune_epoch);

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

		#verify hash from DB equals hash of id + key from URL
		if ( ! password_verify($id . $key, $hash) ) {
			throw new Exception('This secret can not be found!');
		}

		#decrypt secret with the static key, and then with url key
		$secret = encrypt_decrypt(false, getStaticKey(), $iv, $secret);
		$secret = encrypt_decrypt(false, $key, $iv, $secret);

		#delete secret and verify it's gone
		if ( ! deleteSecret($db, $id) ) {
			# if we cant destroy it, dont give the secret out
			throw new Exception('Failed to destroy secret!');
		}

		#close db
		$db = null;

		#return decrypted text
		return $secret;
	}

	function get_template_html($formdata, $editable = false) {
		$html = "";
		$copy = "";

		// Get the template name, and convert any + (space in HTML) back to space
		$template = explode("=",$formdata['select'])[0];
		$template = str_replace("+", " ", $template);

		// If the template does not contain any of the HTML input types,
		// return the contents as a plain file.
		$file = file_get_contents("templates/$template.txt", true);
		if (!preg_match_all('/(radio|select|number|textarea|datetime|date|time|checkbox)/', $file)) {
			return $file;
		}

		// Disable all controls and hide the copy buttons
		// if the template is in edit mode.
		$disabled = $editable ? null : "disabled";
		$copyButton = null;

		// Read the contents of the template file line by line.
		$file_handle = fopen("templates/$template.txt", "r");
		foreach (get_all_lines($file_handle) as $line) {
			// Get the name, element and properties to create the HTML elements
			preg_match(
				'/(?<name>.+):\s+(?<element>radio|select|number|textarea|datetime|date|time|checkbox)?(\((?<props>.+)?\))?/',
				$line,
				$matches
			);

			// Get the name of the element to encode as a base64 string id
			$name = trim($matches["name"]);
			$id = base64_encode($name);

			// Get the type of the HTML element
			$elementType = null;
			if (array_key_exists("element", $matches)) {
				$elementType = trim($matches["element"]);
			}

			// Get the properties for the HTML element
			$props = null;
			if (array_key_exists("props", $matches)) {
				$props = trim($matches["props"]);
			}

			// Skip if the element is a known element from FlashPaper
			if (preg_match('/(secret|select|submit).?/', $name)==false) {
				$new = null;
				$style = null;
				$checked = null;
				$value = null;
				$selected = null;

				// Extracts the value from the form
				if (array_key_exists($name, $formdata)) {
					$value = trim($formdata[$name]);
				}

				// Add the HTML control based on the element type from the template
				switch ($elementType) {
					case "radio":
						$i = 0;
						foreach(explode(",", $props) as $exploded) {
							$exploded = trim($exploded);
							if ($value == $exploded) {$checked = "checked";}
							$new = "$new<input type='radio' id='${name}_$i' name='$name' value='$exploded' $disabled $checked/><label for='${name}_$i' >$exploded</label>";
							$checked = null;
							$i++;
						}
						break;
					case "select":
						$options = null;
						foreach(explode(",", $props) as $exploded) {
							$exploded = trim($exploded);
							if ($value == $exploded) {$selected = "selected";}
							$options = "$options<option $selected>$exploded</option>";
						}
						$new = "$new<select id='$id' name='$name' value='$value' $disabled >$options</select>";
						break;
					case "number":
						list($min,$max) = explode(",", $props);
						$new = "<input type='number' id='$id' name='$name' value='$value' $disabled min='$min' max='$max'>";
						break;
					case "date":
					case "time":
						$new = "<input type='$elementType' id='$id' name='$name' value='$value' $disabled />";
						break;
					case "datetime":
						$new = "<input type='datetime-local' id='$id' name='$name' value='$value' $disabled />";
						break;
					case "checkbox":
						$checked = $value == "on" ? "checked" : null;
						$new = "<input type='checkbox' id='$id' name='$name' $checked $disabled />";
						break;
					case "textarea":
						$new = "<textarea id='$id' name='$name' class='form-control' $disabled >$value</textarea>";
						$style = "style='width: inherit'";
						break;
					default:
						if ($name != null) {
							$new = "<input type='input' id='$id' name='$name' value='$value' $disabled />";
						}
						break;
				}

				// Prevents adding blank lines to the HTML controls
				if ($new != null) {
					if ($disabled != null) {
						$copyButton = "<input type='image' class='icon' src='/img/copy.svg' onclick='copyText(\"$id\", \"$elementType\")'/>";
					}
					$html = "$html<ul><li><label for='$id'>$name</label></li><li $style>$new</li><li>$copyButton</li></ul></br>\n";
					$copy = "$copy$name:$value\n";
				}
			}
		}
		fclose($file_handle);

		$html = "<div class='html_secret'>$html</div>";
		$html = "$html<textarea id='copy' style='display: none;'>$copy</textarea>";

		return $html;
	}

	function get_all_lines($file_handle) {
		while (!feof($file_handle)) {
			yield fgets($file_handle);
		}
	}

?>
