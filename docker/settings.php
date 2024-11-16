<?php
	defined("_DIRECT_ACCESS_CHECK") or exit();

	$settings = [
		"site_title" => getenv('SITE_TITLE') ?: "FlashPaper :: Self-Destructing Message",
		"site_logo" => getenv('SITE_LOGO') ?: "img/logo.png",
		"display_logo" => getenv('DISPLAY_LOGO') ?: "true",
		"display_title" => getenv('DISPLAY_TITLE') ?: "false",
		"custom_css" => getenv('CUSTOM_CSS') ?: "false",
		"bootstrap_theme" => getenv('BOOTSTRAP_THEME') ?: "flashpaper",
		"return_full_url" => getenv('RETURN_FULL_URL') ?: "true",
		"base_url" => getenv('BASE_URL') ?: "",
		"max_secret_length" => getenv('MAX_SECRET_LENGTH') ?: 3000,
		"announcement" => getenv('ANNOUNCEMENT') ?: "",
		'prune' => [
			'enabled' => getenv('PRUNE_ENABLED') ?: "true",
			'min_days' => getenv('PRUNE_MIN_DAYS') ?: 365,
			'max_days' => getenv('PRUNE_MAX_DAYS') ?: 730,
		],
		"messages" => [
			"error_secret_too_long" => getenv('MESSAGES_ERROR_SECRET_TOO_LONG') ?: "Input length too long",

			"submit_secret_header" => getenv('MESSAGES_SUBMIT_SECRET_HEADER') ?: "Create A Self-Destructing Message",
			"submit_secret_subheader" => getenv('MESSAGES_SUBMIT_SECRET_SUBHEADER') ?: "",
			"submit_secret_button" => getenv('MESSAGES_SUBMIT_SECRET_BUTTON') ?: "Encrypt Message",

			"view_code_header" => getenv('MESSAGES_VIEW_CODE_HEADER') ?: "Self-Destructing URL",
			"view_code_subheader" => getenv('MESSAGES_VIEW_CODE_SUBHEADER') ?: "Share this URL via email, chat, or another messaging service. It will self-destruct after being viewed once.",

			"confirm_view_secret_header" => getenv('MESSAGES_CONFIRM_VIEW_SECRET_HEADER') ?: "View this secret?",
			"confirm_view_secret_button" => getenv('MESSAGES_CONFIRM_VIEW_SECRET_BUTTON') ?: "View Secret",

			"view_secret_header" => getenv('MESSAGES_VIEW_SECRET_HEADER') ?: "Self-Destructing Message",
			"view_secret_subheader" => getenv('MESSAGES_VIEW_SECRET_SUBHEADER') ?: "This message has been destroyed",
		]
	];
?>
