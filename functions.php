<?php 

    function print_html_form() {

        readfile("pages/main.html");
    }

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
        $file = fopen("secrets/$filename", "r") or die("<h2>This secret can not be found!</h2>");
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

?>
