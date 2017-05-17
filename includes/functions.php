<?php

    function encrypt_decrypt($action, $password, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = $password;
        $secret_iv = sha1($password);
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    function write_file($filename, $text) {
        $file = fopen("secrets/$filename", "w") or die("Unable to write file!");
        fwrite($file, $text);
        fclose($file);
    }

    function read_file($filename) {
        $file = fopen("secrets/$filename", "r") or die("This secret can not be found!");
        $text = fread($file,filesize("secrets/$filename"));
        fclose($file);

        return $text;
    }

    function delete_file($filename) {
        unlink("secrets/$filename");
    }

    function random_str() {
        for ($i = -1; $i <= 32; $i++) {
          $bytes = openssl_random_pseudo_bytes($i, $cstrong);
          $out = base64_encode($bytes);
        }
        return $bytes;
    }

    function base64_encode_url($input) {
        return strtr(base64_encode($input), '+/=', '-_$');
    }

    function base64_decode_url($input) {
        return base64_decode(strtr($input, '-_$', '+/='));
    }

    function is_valid_base64($base64text) {
      //This isn't perfect, but it'll cath 99% of non-valid Base64 endoding
      $decoded = base64_decode($base64text, true);

      // Check if there is no invalid character in string
      if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

      // Decode the string in strict mode and send the response
      if (!base64_decode($string, true)) return false;

      // Encode and compare it to original one
      if (base64_encode($decoded) != $string) return false;

      return true;
    }

    function store_secret($text) {
      $rand_pass = random_str();
      $enc_text = encrypt_decrypt("encrypt", $rand_pass, $text);
      $dec_text = encrypt_decrypt("decrypt", $rand_pass, $enc_text);
      $sha_pass = hash("sha512", $rand_pass);
      $base_pass = base64_encode_url($rand_pass);

      write_file($sha_pass, $enc_text);

      //Return the 'k' portion of the URL
      return $base_pass;
    }

    function retrieve_secret($key) {
      $password = base64_decode_url($key);
      $sha_pass = hash("sha512", $password);
      $enc_text = read_file($sha_pass);
      $dec_text = encrypt_decrypt("decrypt", $password, $enc_text);
      delete_file($sha_pass);

      return $dec_text;
    }

?>
