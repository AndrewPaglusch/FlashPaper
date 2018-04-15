<?php

    /* //DEBUG
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1); */

    require_once "includes/functions.php";

    $style = true;

    //Disable all CSS and extra HTML to make automation easier
    if (isset($_POST['nostyle'])) { $style = false; }

    if (isset($_GET['k'])) {
        // **User is trying to view a secret**

        //This is to prevent 'preview bots' from automatically viewing the secret and thus destroying it
        if ((isset($_GET['accept']) && $_GET['accept'] == "true") || $style == false) {
            //User has confirmed they'd like to see the secret or the user has 'nostyle' set in the URL

            $secret = retrieve_secret($_GET['k']);

            if ($style == true) {
                $message = htmlentities($secret);
                $message_title = "Self-Destructing Message";
                $message_subtitle = "This message has been destroyed";

                include('html/header.html');
                include('pages/message.php');
                include('html/footer.html');
            } else {
                //Getting secret with now style
                echo $secret;
            }

        } else {
            //TODO: http://stackoverflow.com/a/41703064
            echo "<h2>View the secret?<br /><a href='?k=" . $_GET['k'] . "&accept=true'>Yes</a></h2>";
        }
    } elseif (isset($_POST['submit']) || $style == false) {
        // **User is trying to submit a secret**

        $incoming_text = $_POST['secret'];

        //If this is not being submitted via the GUI,
        //Incoming text should be base64 endoded. Try to decode
        if ($style == false) {
          if (is_valid_base64($incoming_text) == true) {
            $incoming_text = base64_decode($incoming_text);
          } else {
            die("ERROR: Input text is not in valid Base64 format");
          }
        }

        $k = store_secret($incoming_text);
        $message = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/?k=" . $k;

        if ($style == true) {
          //Display the retreival URL is a pretty format
          $message_title = "Self-Destructing URL";
          $message_subtitle = "";

          include('html/header.html');
          include('pages/message.php');
          include('html/footer.html');
        } else {
          //Display the retreival URL in plain text
          echo $message;
        }
	} else {
        // **User is loading the main page**
        if ($style == true) {

          //Get template from URL (if any)
          $template_text = "";
          if (isset($_GET['t']) && $_GET['t'] != "") {
            $template_text = read_file('templates/' . $_GET['t'] . '.txt');
          }

          include('html/header.html');
          include('html/form.html');
          include('html/footer.html');
        } else {
          //User didn't give us the required options, but they passed 'nostyle'
          echo "You have requested that we not show you any style/html by addding the 'nostyle' option to the POST data in this request. ";
          echo "You will need to either remove this option, or submit POST data for 'secret'";
        }
    }
?>
