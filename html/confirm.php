<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
<html>
	<head>
		<title>FlashPaper :: Self-Destructing Message</title>
		<!-- Meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<style>
			#form-div {
			background-color:rgba(72,72,72,0.1);
			padding-left:35px;
			padding-right:35px;
			padding-top:35px;
			padding-bottom:35px;
			margin:30px; 
			width: 60%;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			}
			.form-control[readonly] {
			background-color:#ffffff !important;
			opacity:1
			}
			fieldset {
			margin:10px;
			border: 0;
			}
			input[type=submit]:hover {
			background-color: #67b2bc;
			color: white;
			}
		</style>
	</head>
	<body>
		<div id="form-div" class="mx-auto">
			<fieldset style="text-align: center">
				<div class="form-group row float-middle">
					<div class="col">
						<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 15px; font-size: 30px; font-weight: bold; padding-bottom: 1%">View this secret?</label>
<br />
						<a href="?k=<?php echo $_GET['k'] ?>&accept=true" class="btn btn-primary w-20 mx-auto">View Secret</a>
					</div>
				</div>
			</fieldset>
		</div>
	</body>
</html>
