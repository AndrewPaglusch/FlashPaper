<?php
	define('_DIRECT_ACCESS_CHECK', 1);
	require_once("includes/sanitycheck.php"); # check everything before we proceed
	require_once("settings.php"); # load settings
	require_once("includes/functions.php"); # load functions

	# display secret retrieval url in json format if requested
	if (isset($_POST['json']) && !empty($_POST['secret'])) {
		header("Content-Type: application/json");
		die(display_secret_code(true));
	}

	require_once('html/header.php'); # display header

	if (!empty($_GET['k'])) {
		try {
			confirm_display_secret(); # ask confirmation before showing secret
		} catch (Exception $e) { display_error($e); }

	} elseif (!empty($_POST['k'])) {
		try {
			display_secret(); # user confirmed viewing the secret
		} catch (Exception $e) { display_error($e); }

	} elseif (isset($_POST['submit']) && !empty($_POST['secret'])) {
		try {
			display_secret_code($json); # secret submitted. display url/code
		} catch (Exception $e) { display_error($e); }

	} else {
		if ($settings['prune']['enabled']) {
			# clean up pruned secrets (if enabled)
			# don't throw exceptions to user, since this should be transparent to them
			try {
				$db = connect();
				secretCleanup($db);
			} catch (Exception $e) { }
		}

		try {
			display_form(); # display main page
		} catch (Exception $e) { display_error($e); }
	}

	require_once('html/footer.php'); # display footer

	function display_form() {
		global $settings;

		$template_text = '';
		if ( !empty($_GET['t']) ) {
			$safe_path = 'templates/' . basename($_GET['t']) . '.txt';
			if ( file_exists($safe_path) ) { $template_text = file_get_contents($safe_path); }
		}
		require_once('html/submit_secret.php');
	}

	function confirm_display_secret() {
		global $settings;

		require_once('html/confirm.php');
	}

	function display_secret() {
		global $settings;

		$secret = retrieve_secret($_POST['k']);
		$message = htmlentities($secret);
		require_once('html/view_secret.php');
	}

	function display_secret_code($return_only_json = false) {
		global $settings;

		# verify secret length isnt too long
		# newlines are always \r\n, so replace with \n so the strlen count is accurate
		if ( strlen(str_replace("\r\n", "\n", $_POST['secret'])) > $settings['max_secret_length'] ) {
			throw new exception($settings['messages']['error_secret_too_long']);
		}

		$message = store_secret($_POST['secret'], $settings);

		if ($settings['return_full_url'] == true) {
			$message = build_url($message);
		}

		if ($return_only_json) {
			return json_encode(array("url" => $message), JSON_UNESCAPED_SLASHES);
		}

		require_once('html/view_code.php');
	}

	function build_url($k) {
		global $settings;

		# use the base_url value from settings if it's there
		if ( !empty($settings['base_url']) ) {
			$base_url = $settings['base_url'];
			$base_url = str_replace("index.php", "", $base_url); # remove index.php from base_url if it's there
			$base_url = rtrim($base_url, '/') . '/'; # make sure base_url ends with a '/'
			return $base_url . "?k={$k}";
		}

		# try our best to predict the base url of the FlashPaper instance
		$scheme = (isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME'] . '://' : 'https://';
		$hostname = $_SERVER['HTTP_HOST']; # my.flashpaper.io
		$path = strtok($_SERVER['REQUEST_URI'], '?'); # strip any GET vars from url (like ?t=bla)
		$path = str_replace("index.php","",$path); # remove index.php from path if it's there
		$path = preg_replace('/(\/+)/','/',$path); # strip any duplicate /'s from path
		$path = rtrim($path, '/') . '/'; # make sure path ends with /
		$args = "?k={$k}"; # /?k=a1b2c3d4...
		return "{$scheme}{$hostname}{$path}{$args}";
	}

	function display_error($exception) {
		$error_message = $exception->getMessage();
		require_once('html/error.php');
	}

?>
