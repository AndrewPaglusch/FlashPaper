<?php
	define("_DIRECT_ACCESS_CHECK", 1);
	require_once("includes/sanitycheck.php"); # check everything before we proceed
	// require_once("settings.php"); # load settings
	require_once("includes/functions.php"); # load functions

	if ( !empty($_GET["select"]) ) {
		$safe_path = "templates/" . basename($_GET["select"]) . ".txt";
		if ( file_exists($safe_path) ) {
			$template_text = file_get_contents($safe_path);
			$formdata["select"] = $_GET["select"];
			echo get_template_html($formdata, true);
		} else {
			echo "TEMPLATE_NOT_FOUND";
		}
	}
?>