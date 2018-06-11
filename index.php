<?php
	#Settings
	define('RETURN_FULL_URL', true);

	define('_DIRECT_ACCESS_CHECK', 1);
	require_once "includes/functions.php";

	include('html/header.php');

	if (isset($_GET['k'])) {
		#**User is trying to view a secret**
		if (isset($_GET['accept']) && $_GET['accept'] == "true") {
			try {
				$secret = retrieve_secret($_GET['k']);
				$message = htmlentities($secret);
				$message_title = "Self-Destructing Message";
				$message_subtitle = "This message has been destroyed";

				include('html/message.php');
			} catch (Exception $e) {
				$error_message = $e->getMessage();
				include('html/error.php');
			}
		} else {
			#This is to prevent 'preview bots' from automatically viewing the secret and thus destroying it
			include('html/confirm.php');
		}
	} elseif (isset($_POST['submit'])) {
		#**User just submitted a secret. Show them the generated URL**
		try {
			$incoming_text = $_POST['secret'];
			$k = store_secret($incoming_text);

			if (constant("RETURN_FULL_URL") == true) {
				$message = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/?k=" . $k;
			} else {
				$message = $k;
			}

			$message_title = "Self-Destructing URL";
			$message_subtitle = "";

			include('html/message.php');
		} catch (Exception $e) {
			$error_message = $e->getMessage();
			include('html/error.php');
		}
	} else {
		#**User is loading the main page**

		#Get template from URL (if any)
		$template_text = "";

		try {
			if (isset($_GET['t']) && $_GET['t'] != "") {
				$template_text = read_file('templates/' . basename($_GET['t'] . '.txt'));
			}

			$message_title = "Self-Destructing Message";
			$message_subtitle = "";

			include('html/form.php');
		} catch (Exception $e) {
			$error_message = "Template can not be found!";
			include('html/error.php');
	   	}
	}

	include('html/footer.php');
?>
