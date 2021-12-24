<?php
	defined('_DIRECT_ACCESS_CHECK') or exit();

	$settings = [
		'site_title' => 'FlashPaper :: Self-Destructing Message',
		'return_full_url' => true,
		'max_secret_length' => 3000,
		'announcement' => '',
		'prune' => [
			'enabled' => true,
			'min_days' => 365,
			'max_days' => 730
		],
		'messages' => [
			'error_secret_too_long' => 'Input length too long',

			'submit_secret_header' => 'Create A Self-Destructing Message',
			'submit_secret_subheader' => '',
			'submit_secret_button' => 'Encrypt Message',

			'view_code_header' => 'Self-Destructing URL',
			'view_code_subheader' => 'Share this URL via email, chat, or another messaging service. It will self-destruct after being viewed once.',

			'confirm_view_secret_header' => 'View this secret?',
			'confirm_view_secret_button' => 'View Secret',

			'view_secret_header' => 'Self-Destructing Message',
			'view_secret_subheader' => 'This message has been destroyed',
		]
	];
?>
