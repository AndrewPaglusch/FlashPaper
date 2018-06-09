<?php

    defined('_DIRECT_ACCESS_CHECK') or exit(); 

    $bcrypt_options = ['cost' => 11, 'salt' => base64_decode('6bDvAudGJKkTweOWIlxVbTWjmj/8kDL9giAPkGDN7qhCi+jM50eS/2ijplJ1jf7T1+ZF1pUmtw2xlxsb0hlr3w==')];
    $static_key = base64_decode('tGOrlqr/97qxQwby+uwsbReOLxTLgrMntDKX/Uj4LMy6YSSQ9Xr4DjMgKVhnUT2pZ/YFJUo/qE/xBMD5dZBF9ZBZTnPNz+Pnez1OazpoEAy2M3vE7N/kQ4tP7kA98jf+NCKqi8MLJ6hQPMPXFuciIQsKUNWc6clJ+q5GwJAxikyxy8VCnDgOEoV0u6GpVB7syrB1OlvORAWsB7wqkq2XmiZnRVJsnz/td6kBhnwPE5F7ghlncZcyXjuL86M/bFlrqtm6pH6mVLWIAeRFdH7jVdgB/ihVsnaxXXkHnY9AZEzmuk19r+IiRHIv+ft299t//ddFM5lGduwqKCJ8tfpWFQ==');
    $static_iv = base64_decode('PhIumEnRYCnv+DlQOO2rvw==');

    function encrypt_decrypt($encrypt, $key, $iv, $string) {
        if( $encrypt == true) {
            return openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);
        } else {
            return openssl_decrypt($string, 'AES-256-CBC', $key, 0, $iv);
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

    function store_secret($text) {
        global $bcrypt_options, $static_key, $static_iv;
        
        #generate random key
        $rand_key = random_str(32);

        #generate random iv
        $iv = random_str(16);

        #base64 encode the key (for URL)
        $base_key = base64_encode_mod($iv . $rand_key);
        
        #encrypt text with random key
        $enc_text = encrypt_decrypt(true, $rand_key, $iv, $text);
        
        #encrypt text with static key
        $enc_text = encrypt_decrypt(true, $static_key, $static_iv, $enc_text);
        
        #generate hash of key & base64 it
        $filename = base64_encode_mod(password_hash($iv . $rand_key, PASSWORD_BCRYPT, $bcrypt_options));
        
        #write encrypted text to disk. filename is hash of key
        write_file("secrets/" . $filename, $enc_text);
        
        #return base64 of key
        return $base_key;
    }

    function retrieve_secret($k) {
        global $bcrypt_options, $static_key, $static_iv;

        #validate length of key - must be 48 chars (iv = 16, key = 32)
        if ( strlen(base64_decode_mod($k)) != 48 ) {
            throw new Exception('Malformed key!');
        }
        
        #decode key from url with modified base64
        $key = substr(base64_decode_mod($k), -32);
		
        #decode iv from url
        $iv = substr(base64_decode_mod($k), 0, 16);

        #generate hash of key & base64 it
        $filename = base64_encode_mod(password_hash($iv . $key, PASSWORD_BCRYPT, $bcrypt_options));
        
        #read file that is named same as the hash of key
        $enc_text = read_file("secrets/" . $filename);
        
        #decrypt contents of file with the static key
        $dec_text = encrypt_decrypt(false, $static_key, $static_iv, $enc_text);
        
        #decrypt contents of file with the base64 decoded key
        $dec_text = encrypt_decrypt(false, $key, $iv, $dec_text);
        
        #delete the file from disk
        delete_file("secrets/" . $filename);
        
        #return decrypted text
        return $dec_text;
    }

?>
