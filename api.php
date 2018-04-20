<?php

    require_once "includes/functions.php";
      
    if (isset($_GET['k'])) {
        #retrieve secret
        try {
            echo sendSuccess(retrieve_secret($_GET['k']));
        } catch (Exception $e) {
            echo sendFailure($e); 
        }
    } elseif (isset($_POST['k'])) {
        #store secret
        try {
            echo sendSuccess(store_secret(base64_decode($_POST['k'])));
        } catch (Exception $e) {
          echo sendFailure($e);
        }
    } else {
        echo sendFailure("Invalid input");
    }

    function sendSuccess(message) {
        return "[{\"SUCCESS\":true, \"message\": \"${message}\"}]";
    }

    function sendFailure(error) {
        return "[{\"SUCCESS\":false, \"message\": \"${error}\"}]";      
    }
?>
