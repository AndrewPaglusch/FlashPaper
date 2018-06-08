<?php

    defined('_DIRECT_ACCESS_CHECK') or exit(); 

    $bcrypt_options = ['cost' => 11, 'salt' => '#`jQ9`@qryyF]`uz,-a,i|}^]=a8LT\$'];

    #used for second round of encryption. can be destroyed to invalidate all secrets on disk
    $static_key = 'FAg&Se(YO3h!Ib?K2W^>Gv[n?h)w)!y>';

    function encrypt_decrypt($encrypt, $key, $string) {
        $iv = substr(hash('sha256', $key), 0, 16); #sha256 sha1 of key and get first 16 bytes

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

    function store_secret($text) {
      global $bcrypt_options, $static_key;

      #generate random key
      $rand_key = random_str();

      #base64 encode the key (for URL)
      $base_key = base64_encode_mod($rand_key);

      #encrypt text with random key
      $enc_text = encrypt_decrypt(true, $rand_key, $text);

      #encrypt text with static key
      $enc_text = encrypt_decrypt(true, $static_key, $enc_text);

      #generate hash of key & base64 it
      $filename = base64_encode_mod(password_hash($rand_key, PASSWORD_BCRYPT, $bcrypt_options));

      #write encrypted text to disk. filename is hash of key
      write_file("secrets/" . $filename, $enc_text);

      #return base64 of key
      return $base_key;
    }

    function retrieve_secret($key) {
      global $bcrypt_options, $static_key;
      
      #decode key from url with modified base64
      $key = base64_decode_mod($key);

      #generate hash of key & base64 it
      $filename = base64_encode_mod(password_hash($key, PASSWORD_BCRYPT, $bcrypt_options));

      #read file that is named same as the hash of key
      $enc_text = read_file("secrets/" . $filename);

      #decrypt contents of file with the static key
      $dec_text = encrypt_decrypt(false, $static_key, $enc_text);

      #decrypt contents of file with the base64 decoded key
      $dec_text = encrypt_decrypt(false, $key, $dec_text);

      #delete the file from disk
      delete_file("secrets/" . $filename);

      #return decrypted text
      return $dec_text;
    }

?>
