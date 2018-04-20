<?php

	require_once "includes/functions.php";
	
	if (isset($_GET['k'])) {
		#**User is trying to view a secret**
		if ($_GET['accept'] == "true") {
			try {
				$secret = retrieve_secret($_GET['k']);
				$message = htmlentities($secret);
				$message_title = "Self-Destructing Message";
				$message_subtitle = "This message has been destroyed";
				
				include('html/header.html');
				include('pages/message.php');
				include('html/footer.html');
			} catch (Exception $e) {
				die($e->getMessage());
			}
		} else {
			#This is to prevent 'preview bots' from automatically viewing the secret and thus destroying it
			echo "<h2>View the secret?<br /><a href='?k=" . $_GET['k'] . "&accept=true'>Yes</a></h2>";
		}		
	} elseif (isset($_POST['submit'])) {
		#**User is trying to submit a secret**
		try {
			$incoming_text = $_POST['secret'];
			$k = store_secret($incoming_text);
		
			$message = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/?k=" . $k;
			$message_title = "Self-Destructing URL";
			$message_subtitle = "";
			
			include('html/header.html');
			include('pages/message.php');
			include('html/footer.html');
		} catch (Exception $e) {
			die($e->getMessage());
		}
	} else {
		#**User is loading the main page**
		#Get template from URL (if any)
		$template_text = "";
		if (isset($_GET['t']) && $_GET['t'] != "") {
			$template_text = read_file('templates/' . $_GET['t'] . '.txt');
		}
		
		include('html/header.html');
		include('html/form.html');
		include('html/footer.html');
	}
?>

