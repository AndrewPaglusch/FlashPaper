<?php

	require_once "includes/functions.php";

	$useJson = true;
	if ($_GET['json'] == "false") {
		$useJson = false;
	}

	if (isset($_GET['k'])) {
		#retrieve secret
		try {
			echo sendJSON(true, retrieve_secret($_GET['k']));
		} catch (Exception $e) {
			echo sendJSON(false, "Secret can not be found!");
		}
	} elseif (isset($_POST['k'])) {
		#store secret
		try {
			echo sendJSON(true, store_secret(base64_decode($_POST['k'])));
		} catch (Exception $e) {
			echo sendJSON(false, $e);
		}
	} else {
		echo sendJSON(false, "Invalid input");
	}

	function sendJSON($success, $message) {
		global $useJson;

		if ($useJson == true) {
			if ($success) {
				return "[{\"SUCCESS\":true, \"message\": \"${message}\"}]";
			} else {
				return "[{\"SUCCESS\":false, \"message\": \"${message}\"}]";
			}
		} else {
			return $message;
		}
	}
?>
