<?php

require_once "includes/functions.php";

if (isset($_GET['k'])) {
	#retrieve secret
	echo retrieve_secret($_GET['k']);
} elseif (isset($_POST['k'])) {
	#store secret
	$incoming_text = base64_decode($_POST['k']);
	echo store_secret($incoming_text);
} else {
	echo "Invalid Input!";
}
?>
