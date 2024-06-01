<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
	<head>
		<!--
		######################################################################################
		# Copyright (c) 2017-<?php echo date("Y"); ?> Andrew Paglusch                                            #
		# https://raw.githubusercontent.com/AndrewPaglusch/FlashPaper/master/LICENSE         #
		######################################################################################
		-->
		<title><?php echo $settings['site_title'] ?></title>
		<!-- Meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Font Awesome CSS -->
		<link rel="stylesheet" href="./css/fontawesome.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="./css/solid.min.css" rel="stylesheet" />
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<style>
			#form-div {
				background-color:rgba(72,72,72,0.1);
				padding-left:35px;
				padding-right:35px;
				padding-top:35px;
				padding-bottom:50px;

				margin:30px;
				border-radius: 7px;
				-moz-border-radius: 7px;
				-webkit-border-radius: 7px;
			}
			.form-control[readonly] {
				opacity:1
			}
			textarea {
				width: 100%;
				height: 200px;
				padding: 12px 20px;
				box-sizing: border-box;
				border: 2px solid #ccc;
				border-radius: 4px;
				resize: none;
			}
			fieldset {
				margin:10px;
				border: 0;
			}
			.btn-primary {
				background-color: #4ca2ae;
				border: none;
				color: white;
				padding: 12px 28px;
				text-decoration: none;
				font-size: 16px;
				margin: 4px 2px;
				cursor: pointer;

				-webkit-transition-duration: 0.4s; /* Safari */
				transition-duration: 0.4s;
			}
			.btn-primary:hover {
				background-color: #67b2bc;
				color: white;
			}
			.col-8 {
				width: 320px !important;
			}
			.col-4 {
				width: 100% !important;
			}
		</style>
		<script src="./js/color-toggle.js" defer></script>
	</head>
	<body onUnload="document.getElementById('secret').value = ''">
	<script>
			if (location.protocol != 'https:') {
				document.write('<div style="padding-top: 1%" class="container"><div class="alert alert-danger"><strong>Danger!</strong> This site is not being accessed over an encrypted connection. Do NOT input any sensitive information!</div></div>');
			}
			function copyText() {
				var textToCopy = document.getElementById("copy");
				textToCopy.select();
				document.execCommand("copy");
			}
	</script>
		<header>
			<nav class="navbar navbar-expand-lg">
		  		<div class="container-fluid">
			    		<a class="navbar-brand" href="">
					<?php
						if ( $settings['site_logo'] != '' && $settings['display_logo'] == 'true' ) {
							echo '<img src="'. $settings['site_logo'] .'" alt="Logo" width="200" class="d-inline-block align-middle">';
						}
					?>
					<?php
						if ( $settings['display_title'] == 'true') {
							echo $settings['site_title'];
						}
					?>
					</a>
					<span class="navbar-text">
						<li class="nav-item align-items-center d-flex" >
	 						<i class="fa-solid fa-sun"></i>
							<!-- Default switch -->
	  						<div class="ms-2 form-check form-switch">
	    							<input class="form-check-input" type="checkbox" role="switch" id="themingSwitcher" />
	  						</div>
	  						<i class="fa-solid fa-moon"></i>
						</li>
			      		</span>
		  		</div>
			</nav>
			<?php
				if ( $settings['announcement'] != '' ) {
					echo '<div style="padding-top: 1%" class="container"><div class="alert alert-warning"><strong>Announcement:</strong> ' . $settings['announcement'] . '</div></div>';
				}
			?>
		</header>
