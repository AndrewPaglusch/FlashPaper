<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- 
		######################################################################################
		# Copyright (c) 2017-2019 Andrew Paglusch                                            #
		# https://raw.githubusercontent.com/AndrewPaglusch/FlashPaper/master/LICENSE         #
		######################################################################################
		-->
		<title><?php echo $settings['site_title'] ?></title>
		<!-- Meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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
				background-color:#ffffff !important;
				opacity:1
			}
			textarea {
				width: 100%;
				height: 200px;
				padding: 12px 20px;
				box-sizing: border-box;
				border: 2px solid #ccc;
				border-radius: 4px;
				background-color: #f8f8f8;
				resize: none;
			}
			fieldset {
				margin:10px;
				border: 0;
			}
			input[type=submit] {
				background-color: #4ca2ae;
				border: none;
				color: white;
				padding: 12px 28px;
				text-decoration: none;
				font-size: 20px;
				margin: 4px 2px;
				cursor: pointer;

				-webkit-transition-duration: 0.4s; /* Safari */
				transition-duration: 0.4s;
			}
			input[type=submit]:hover {
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
	</head>
	<body onUnload="document.getElementById('secret').value = ''">
		<script>
			if (location.protocol != 'https:') {
				document.write('<div style="padding-top: 1%" class="container"><div class="alert alert-danger"><strong>Danger!</strong> This site is not being accessed over an encrypted connection. Do NOT input any sensitive information!</div></div>');
			}
		</script>
