<?php
	#Settings
	define('RETURN_FULL_URL', true);
	define('MAX_INPUT_LENGTH', 3000);
	define('DATA_DIR', 'data');

	define('_DIRECT_ACCESS_CHECK', 1);

	# check everything before we proceed
	require_once("includes/sanitycheck.php");

	require_once "includes/functions.php";

	require_once('html/header.php');

	if (isset($_GET['k'])) {
		#**User is trying to view a secret**
		require_once('html/confirm.php');
	} elseif (isset($_POST['k'])) {
		#**User confirmed viewing the secret**
		try {
			$secret = retrieve_secret($_POST['k']);
			$message = htmlentities($secret);
			$message_title = "Self-Destructing Message";
			$message_subtitle = "This message has been destroyed";

			require_once('html/message.php');
		} catch (Exception $e) {
			$error_message = $e->getMessage();
			require_once('html/error.php');
		}
	} elseif (isset($_POST['submit'])) {
		#**User just submitted a secret. Show them the generated URL**
		try {
			$incoming_text = $_POST['secret'];

			if ( strlen($incoming_text) > constant('MAX_INPUT_LENGTH') ) {
				throw new exception("Input length too long");
			}

			$k = store_secret($incoming_text);

			if (constant('RETURN_FULL_URL') == true) {
				# construct retrieval url
				$scheme = $_SERVER['REQUEST_SCHEME'] . '://'; # https://
				$hostname = $_SERVER['HTTP_HOST']; # my.flashpaper.io
				$path = strtok($_SERVER['REQUEST_URI'], '?'); # strip any GET vars from url (like ?t=bla)
				$path = str_replace("index.php","",$path); # remove index.php from path if it's there
				$path = preg_replace('/(\/+)/','/',$path); # strip any duplicate /'s from path
				$path = rtrim($path, '/') . '/'; # make sure path ends with /
				$args = "?k=${k}"; # /?k=a1b2c3d4...
				$message = "${scheme}${hostname}${path}${args}";
			} else {
				$message = $k;
			}

			$message_title = "Self-Destructing URL";
			$message_subtitle = "";

			require_once('html/message.php');
		} catch (Exception $e) {
			$error_message = $e->getMessage();
			require_once('html/error.php');
		}
	} else {
		#**User is loading the main page**

		#Get template from URL (if any)
		$template_text = "";

		try {
			if (isset($_GET['t']) && $_GET['t'] != "") {
				$template_text = file_get_contents('templates/' . basename($_GET['t'] . '.txt'));
			}

			$message_title = "Self-Destructing Message";
			$message_subtitle = "";

			require_once('html/form.php');
		} catch (Exception $e) {
			$error_message = "Template can not be found!";
			require_once('html/error.php');
	   	}
	}

	require_once('html/footer.php');
?>
