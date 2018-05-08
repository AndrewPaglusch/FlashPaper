<?php
	#Settings
	define('RETURN_FULL_URL', true);

	define('_DIRECT_ACCESS_CHECK', 1);
	require_once "includes/functions.php";
	
	if (isset($_GET['k'])) {
		#**User is trying to view a secret**
		if ($_GET['accept'] == "true") {
			try {
				$secret = retrieve_secret($_GET['k']);
				$message = htmlentities($secret);
				$message_title = "Self-Destructing Message";
				$message_subtitle = "This message has been destroyed";
				
				include('html/header.php');
				include('html/message.php');
				include('html/footer.php');
			} catch (Exception $e) {
				die($e->getMessage());
			}
		} else {
			#This is to prevent 'preview bots' from automatically viewing the secret and thus destroying it
			echo "<h2>View the secret?<br /><a href='?k=" . $_GET['k'] . "&accept=true'>Yes</a></h2>";
		}		
	} elseif (isset($_POST['submit'])) {
		#**User just submitted a secret. Show them the generated URL**
		try {
			$incoming_text = $_POST['secret'];
			$k = store_secret($incoming_text);
		
			if ($RETURN_FULL_URL) {
				$message = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/?k=" . $k;
			} else {
				$message = $k;
			}

			$message_title = "Self-Destructing URL";
			$message_subtitle = "";
			
			include('html/header.php');
			include('html/message.php');
			include('html/footer.php');
		} catch (Exception $e) {
			die($e->getMessage());
		}
	} else {
		#**User is loading the main page**
      
		#Get template from URL (if any)
		$template_text = "";
        
        	try {
				if (isset($_GET['t']) && $_GET['t'] != "") {
					$template_text = read_file('templates/' . basename($_GET['t'] . '.txt'));
	    		}
        	} catch (Exception $e) {
				die("Template can not be found!");
       		}

		$message_title = "Self-Destructing Message";
		$message_subtitle = "";
		
		include('html/header.php');
		include('html/form.php');
		include('html/footer.php');
	}
?>

