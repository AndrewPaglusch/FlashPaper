<?php
	defined('_DIRECT_ACCESS_CHECK') or exit();

	function sanity_check() {
		$errors = [];

		# make sure data dir is writable. doesn't account for missing executable bit
		if ( ! is_writable("./data") ) {
			$errors[] = 'Data directory is not writable';
		}

		# make sure sqlite is installed
		if ( ! in_array("sqlite", PDO::getAvailableDrivers()) ) {
			$errors[] = 'PHP SQLite module is not installed';
		}

		# make sure settings.php exists
		if ( ! file_exists('./settings.php') ) {
			$errors[] = 'The settings.php file is not readable or does not exist';
		}

		# make sure custom.css exists when set
		if ( $settings['custom_css'] == 'true') {
			// Verify that the custom.css file exists
			if ( ! file_exists('./css/custom.css')) {
				$errors[] = 'The custom.css file is not readable or does not exist';
			}
		}

		# make sure bootstrap theme folder exists
		if ( $settings['bootstrap_theme'] != 'flashpaper') {
			$theme_folder_path = './css/bootstrap/' . $bootstrap_theme;
			if ( ! is_dir($theme_folder_path)) {
				$errors[] = 'Error: The bootstrap theme folder "' . $bootstrap_theme . '" does not exist';
			}
		}

		return $errors;
	}

	# perform pre-flight check before we do anything
	$check_results = sanity_check();

	if ( count($check_results) > 0 ) {
		include('html/header.php');
		foreach ($check_results as $issue) { $error_message .= "<li>{$issue}</li>"; }

		echo <<<EOS
		<div style='padding-top: 1%' class='container'>
			<div class='alert alert-danger'>
				<h4 class='alert-heading'>FlashPaper Configuration Issues</h4>
				<p>The following issues are preventing FlashPaper from loading.</p>
				<hr>
				<ul>{$error_message}</ul>
			</div>
		</div>

EOS;
		include('html/footer.php');
		die();
	}
?>
