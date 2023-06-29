<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!--
		######################################################################################
		# Copyright (c) 2017-<?php echo date("Y"); ?> Andrew Paglusch
		# Copyright (c) 2023-<?php echo date("Y"); ?> Ghost Core Developers                                        #
		# https://raw.githubusercontent.com/AndrewPaglusch/FlashPaper/master/LICENSE         #
		######################################################################################
		-->
		<title><?php echo $settings['site_title'] ?></title>
		<!-- Meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="./css/bootstrap_5.0.2.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<style>
			html {
				background-color: #232728;
				font-family: Proxima Nova;
				
			}
			
			.centered-div {
				display: flex;
        		justify-content: center;
			}

			#form-div {
				background-color:#171a1a;
				padding-left:35px;
				padding-right:35px;
				padding-top:35px;
				padding-bottom:50px;
				border: 1px solid #aeff00;
				width: 900px;

				margin:30px;
				border-radius: 7px;
				-moz-border-radius: 7px;
				-webkit-border-radius: 7px;
			}
			.form-control[readonly] {
				color: #fafafa;
				background-color: #232728 !important;
				border: 2px solid #aeff00;
				opacity:1
			}
			.form-control{
				color: #fafafa;
				background-color: #232728;
				border: 2px solid #aeff00;
			}
			textarea {
				width: 100%;
				height: 200px;
				padding: 12px 20px;
				box-sizing: border-box;
				border: 2px solid #aeff00;
				border-radius: 4px;
				resize: none;
				
			}
			
			.form-control:focus {
				color: #fafafa;
				background-color: #232728;
				border-color: #aeff00;
				outline: 0;
				box-shadow: 0 0 0 1px #aeff00;
			}

			.form-group {
				display: flex;
				flex-direction: row;
        		align-items: center;
			}


			fieldset {
				margin:10px;
				border: 0;
			}
			.btn-primary {
				background-color: #aeff00;
				border: none;
				color: #171a1a;
				text-decoration: none;
				font-size: 12pt;
				margin: 4px 2px;
				cursor: pointer;
				border: 1px solid #aeff00;
				height: 50px;
				width: 180px;

				-webkit-transition-duration: 0.4s; /* Safari */
				transition-duration: 0.4s;
			}
			.btn-primary:hover {
				background-color: #171a1a;
				border: 1px solid #aeff00;
				color: #aeff00;
			}
			.col-8 {
				width: 320px !important;
			}
			.col-4 {
				width: 100% !important;
			}

			.form-select {
				background-color: #aeff00;
				color: #171a1a;
				border: 1px solid #aeff00;
				margin: 4px 2px;
				height: 50px;
				width: 180px;
			}
			label {
				color: #aeff00;
			}
			.logo-img {
				width: 420px;
				height:auto;
				padding-top:45px;
			}


			.btn-img {
				height:24px;
				width:auto;
				top:-3px;
				float: left;
				position: relative;
			}

			@font-face {
			font-family: 'Proxima Nova';
			src:url('./assets/FontsFree-Net-proxima_nova_reg-webfont.ttf') format('truetype');
			font-weight: normal;
			font-style: normal;
			}

			.slider {
				width: 35%;
				accent-color: #aeff00;
			}

			.slider-desc {
				font-size: 12pt;
				color: #fafafa;
				margin-right: 25px;
			}

			.slider-value {
				font-size: 12pt;
				color: #aeff00;
				margin-left: 25px;
			}

			.slider-after {
				color: #aeff00;
				margin-left:1ch;
			}

			.form-select:focus {
				color: #fafafa;
				background-color: #232728;
				border-color: #aeff00;
				outline: 0;
				box-shadow: 0 0 0 1px #aeff00;
			}

			.main-label {
				font-family: 'Proxima Nova', arial, serif;
				color:#fafafa; line-height: 1.25;
				font-size: 30px;
			}

			.main-label-green {
				color: #aeff00;
			}


			@media screen and (max-width: 450px) {
				.logo-img {
					width: 60vw;
					height: auto;
				}
				.form-group {
					flex-direction: column;
				}
				.slider-desc {
					font-size: 10pt;
					color: #fafafa;
					margin-right: 10px;
				}
				.slider {
					width: 75%;
				}

				.slider-value {
					font-size: 10pt;
					margin-left: 10px;
				}

				.slider-after {
					font-size:10pt;
				}
				
				.main-label {
					font-size: 16pt;
				}

			}


		</style>
	</head>
	<body onUnload="document.getElementById('secret').value = ''" style="background-color:#232728">
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
		<?php
			if ( $settings['announcement'] != '' ) {
				echo '<div style="padding-top: 1%" class="container"><div class="alert alert-warning"><strong>Announcement:</strong> ' . $settings['announcement'] . '</div></div>';
			}
		?>
		<div class="centered-div" >
			<a href="/">
				<img class="logo-img" src="./assets/Ghost_Secret_logo_full.png">
			</a>
		</div>