<?php
	defined('_DIRECT_ACCESS_CHECK') or exit();
	$data = array("url" => $message);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit();
?>
