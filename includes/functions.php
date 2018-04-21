<?php

    defined('_DEFVAR') or exit(); 

    $bcrypt_options = [
        'cost' => 11,
        'salt' => "7jEKI5ISLaLwmU9xrNuh2JeO54rS4cJdbPwCvifJr8OoKa3Y59RWn67cNaHnGcpvmnnH7AGzB465FpnjdhSu8roJHnjQcrnWCP",
    ];

    function encrypt_decrypt($encrypt, $secret_key, $string) {
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_key), 0, 16); #sha256 sha1 of key and get first 16 bytes

        if( $encrypt == true) {
            return openssl_encrypt($string, "AES-256-CBC", $key, 0, $iv);
        }
        else {
            return openssl_decrypt($string, "AES-256-CBC", $key, 0, $iv);
        }
    }

    function write_file($filename, $text) {
        if ($fp = fopen($filename, "w")) {
            fwrite($fp, $text);
            fclose($fp);
         } else {
            throw new Exception('Unable to write secret!');
        }
    }

    function read_file($filename) {
        if ( file_exists($filename) && ($fp = fopen($filename, "rb")) !== false ) {
            $str = stream_get_contents($fp);
            fclose($fp);
            return $str;
         } else {
            throw new Exception('This secret can not be found!');
        }
    }

    function delete_file($filename) {
        unlink($filename);
    }

    function random_str() {
        for ($i = -1; $i <= 32; $i++) {
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

    function is_valid_base64($base64text) {
      #This isn't perfect, but it'll catch 99% of non-valid Base64 endoding

      #Check if there is no invalid character in string
      if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64text)) return false;

      #Decode the string in strict mode and send the response
      if (!base64_decode($base64text, true)) return false;

      #Encode and compare it to original one
      if (base64_encode(base64_decode($base64text, true)) != $base64text) return false;

      return true;
    }

    function store_secret($text) {
      global $bcrypt_options;

      #generate random password
      $rand_pass = random_str();

      #base64 encode the password (for URL)
      $base_pass = base64_encode_mod($rand_pass);

      #encrypt text with password
      $enc_text = encrypt_decrypt(true, $rand_pass, $text);

      #generate hash of password & base64 it
      $filename = base64_encode_mod(password_hash($rand_pass, PASSWORD_BCRYPT, $bcrypt_options));

      #write encrypted text to disk. filename is hash of password
      write_file("secrets/" . $filename, $enc_text);

      #return base64 of password
      return $base_pass;
    }

    function retrieve_secret($key) {
      global $bcrypt_options;
      
      #decode password from url with modified base64
      $password = base64_decode_mod($key);

      #generate hash of password & base64 it
      $filename = base64_encode_mod(password_hash($password, PASSWORD_BCRYPT, $bcrypt_options));

      #read file that is named same as the hash of password
      $enc_text = read_file("secrets/" . $filename);

      #decrypt contents of file with the base64 decoded password
      $dec_text = encrypt_decrypt(false, $password, $enc_text);

      #delete the file from disk
      delete_file("secrets/" . $filename);

      #return decrypted text
      return $dec_text;
    }

?>
