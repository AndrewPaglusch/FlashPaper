<?php
	define('_DIRECT_ACCESS_CHECK', 1);

	# check everything before we proceed
	require_once("includes/sanitycheck.php");

	# load settings
	require_once("settings.php");

	# load functions
	require_once("includes/functions.php");

	# display header
	require_once('html/header.php');

	if (isset($_GET['k'])) {
		#**User is trying to view a secret**
		require_once('html/confirm.php');

	} elseif (isset($_POST['k'])) {
		#**User confirmed viewing the secret**
		try {
			$secret = retrieve_secret($_POST['k']);
			$message = htmlentities($secret);
			require_once('html/view_secret.php');
		} catch (Exception $e) {
			$error_message = $e->getMessage();
			require_once('html/error.php');
		}

	} elseif (isset($_POST['submit'])) {
		#**User just submitted a secret. Show them the generated URL**
		try {
			$incoming_text = $_POST['secret'];

			if ( strlen($incoming_text) > $settings['max_secret_length'] ) {
				throw new exception("Input length too long");
			}

			$k = store_secret($incoming_text);

			if ($settings['return_full_url'] == true) {
				# construct retrieval url
				if ( isset($_SERVER['REQUEST_SCHEME']) ) {
					$scheme = $_SERVER['REQUEST_SCHEME'] . '://'; # https://
				} else {
					$scheme = 'https://';
				}
				$hostname = $_SERVER['HTTP_HOST']; # my.flashpaper.io
				$path = strtok($_SERVER['REQUEST_URI'], '?'); # strip any GET vars from url (like ?t=bla)
				$path = str_replace("index.php","",$path); # remove index.php from path if it's there
				$path = preg_replace('/(\/+)/','/',$path); # strip any duplicate /'s from path
				$path = rtrim($path, '/') . '/'; # make sure path ends with /
				$args = "?k=${k}"; # /?k=a1b2c3d4...

				# display URL to user
				$message = "${scheme}${hostname}${path}${args}";
				require_once('html/view_code.php');
			} else {
				# display 'k' value of URL to user
				$message = $k;
				require_once('html/view_code.php');
			}
		} catch (Exception $e) {
			$error_message = $e->getMessage();
			require_once('html/error.php');
		}

	} else {
		#**User is loading the main page**

		#Get template from URL (if any)
		$template_text = "";

		if (isset($_GET['t']) && $_GET['t'] != "") {
			$template_text = file_get_contents('templates/' . basename($_GET['t'] . '.txt'));
		}

		require_once('html/submit_secret.php');
	}

	require_once('html/footer.php');
?>
