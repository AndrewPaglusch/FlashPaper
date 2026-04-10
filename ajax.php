<?php
	define("_DIRECT_ACCESS_CHECK", 1);
	require_once("includes/sanitycheck.php"); # check everything before we proceed
	require_once("includes/functions.php"); # load functions

	if ( !empty($_GET["select"]) ) {
		$template_path = "templates/" . urldecode(basename($_GET["select"])) . ".txt";
		$templates = glob('templates/*.txt');

		if ( ! in_array($template_path, $templates, true) ) {
			echo "TEMPLATE_NOT_FOUND";
		} else if ( file_exists($template_path) ) {
			$template_text = file_get_contents($template_path);
			$formdata["select"] = $_GET["select"];
			echo get_template_html($formdata, true);
		}
	}
?>