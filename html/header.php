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
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="./css/bootstrap.min.css">
		<style>
			:root {
				--fp-accent: #4ca2ae;
				--fp-accent-strong: #3d8a95;
				--fp-accent-soft: rgba(76, 162, 174, 0.14);
				--fp-radius: 14px;
			}

			[data-bs-theme="light"] {
				--fp-bg-1: #eef2f6;
				--fp-bg-2: #dfe6ee;
				--fp-surface: rgba(255, 255, 255, 0.85);
				--fp-surface-border: rgba(15, 23, 42, 0.08);
				--fp-shadow: 0 18px 45px -22px rgba(15, 23, 42, 0.45);
				--fp-text: #1c2733;
				--fp-muted: #5b6675;
				--fp-input-bg: #ffffff;
				--fp-input-border: rgba(15, 23, 42, 0.14);
			}

			[data-bs-theme="dark"] {
				--fp-bg-1: #14161c;
				--fp-bg-2: #1e2430;
				--fp-surface: rgba(255, 255, 255, 0.035);
				--fp-surface-border: rgba(255, 255, 255, 0.09);
				--fp-shadow: 0 24px 60px -28px rgba(0, 0, 0, 0.85);
				--fp-text: #e7eaef;
				--fp-muted: #9aa3b2;
				--fp-input-bg: rgba(255, 255, 255, 0.04);
				--fp-input-border: rgba(255, 255, 255, 0.12);
			}

			body {
				min-height: 100vh;
				display: flex;
				flex-direction: column;
				margin: 0;
				color: var(--fp-text);
				background: radial-gradient(1200px 600px at 50% -10%, var(--fp-bg-2), var(--fp-bg-1)) fixed;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
				-webkit-font-smoothing: antialiased;
			}

			.fp-main {
				flex: 1 0 auto;
				width: 100%;
				max-width: 760px;
				margin: 0 auto;
				padding: 28px 20px 56px;
			}

			.fp-header {
				position: relative;
				display: flex;
				justify-content: center;
				align-items: center;
				padding: 34px 24px 6px;
			}
			.fp-brand {
				display: flex;
				align-items: center;
				gap: 12px;
				font-size: 26px;
				font-weight: 700;
				letter-spacing: -0.02em;
				color: var(--fp-text);
				text-decoration: none;
			}
			.fp-brand img { width: 210px; height: auto; }

			.fp-theme-toggle {
				position: absolute;
				top: 20px;
				right: 24px;
				display: flex;
				align-items: center;
				gap: 10px;
				color: var(--fp-muted);
			}
			.fp-theme-toggle svg { width: 16px; height: 16px; }
			.fp-theme-toggle .form-check { margin: 0; min-height: auto; }
			.fp-theme-toggle .form-check-input {
				cursor: pointer;
				background-color: var(--fp-input-border);
				border-color: transparent;
			}
			.fp-theme-toggle .form-check-input:checked {
				background-color: var(--fp-accent);
				border-color: var(--fp-accent);
			}
			.fp-theme-toggle .form-check-input:focus {
				box-shadow: 0 0 0 0.2rem var(--fp-accent-soft);
			}

			#form-div {
				background: var(--fp-surface);
				border: 1px solid var(--fp-surface-border);
				box-shadow: var(--fp-shadow);
				border-radius: var(--fp-radius);
				backdrop-filter: blur(12px);
				-webkit-backdrop-filter: blur(12px);
				padding: 40px 40px 44px;
				margin: 0;
			}

			.fp-title {
				font-size: 28px;
				font-weight: 700;
				letter-spacing: -0.02em;
				line-height: 1.2;
				margin: 0 0 8px;
				color: var(--fp-text);
			}
			.fp-subtitle {
				font-size: 15px;
				line-height: 1.5;
				margin: 0 0 24px;
				color: var(--fp-muted);
				font-weight: 400;
			}
			.fp-subtitle:empty { display: none; }

			.form-control, .form-select {
				background-color: var(--fp-input-bg);
				border: 1px solid var(--fp-input-border);
				color: var(--fp-text);
				border-radius: 10px;
				padding: 12px 14px;
				transition: border-color 0.15s ease, box-shadow 0.15s ease;
			}
			.form-control:focus, .form-select:focus {
				background-color: var(--fp-input-bg);
				border-color: var(--fp-accent);
				box-shadow: 0 0 0 0.2rem var(--fp-accent-soft);
				color: var(--fp-text);
			}
			.form-control::placeholder { color: var(--fp-muted); opacity: 0.8; }
			.form-control[readonly] { opacity: 1; }

			textarea.form-control {
				min-height: 200px;
				resize: vertical;
				line-height: 1.5;
			}
			input#copy { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }

			.btn-primary {
				background: linear-gradient(135deg, var(--fp-accent), var(--fp-accent-strong));
				border: none;
				color: #fff;
				font-weight: 600;
				letter-spacing: 0.01em;
				padding: 12px 26px;
				border-radius: 10px;
				box-shadow: 0 10px 22px -12px rgba(76, 162, 174, 0.9);
				transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
			}
			.btn-primary:hover, .btn-primary:focus {
				background: linear-gradient(135deg, var(--fp-accent), var(--fp-accent-strong));
				color: #fff;
				filter: brightness(1.06);
				transform: translateY(-1px);
				box-shadow: 0 14px 26px -12px rgba(76, 162, 174, 1);
			}
			.btn-primary:active { transform: translateY(0); }

			.fp-actions {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 16px;
				margin-top: 22px;
				flex-wrap: wrap;
			}
			.fp-actions .form-select { width: auto; min-width: 190px; }

			.alert { border-radius: 12px; border: 1px solid transparent; }
			.fp-banner-wrap { max-width: 760px; margin: 12px auto 0; padding: 0 20px; }

			.fp-footer {
				flex-shrink: 0;
				text-align: center;
				padding: 22px 20px;
				border-top: 1px solid var(--fp-surface-border);
				color: var(--fp-muted);
				font-size: 13px;
			}
			.fp-footer a { color: var(--fp-accent); text-decoration: none; }
			.fp-footer a:hover { text-decoration: underline; }

			@media (max-width: 576px) {
				#form-div { padding: 28px 22px 30px; }
				.fp-header { padding: 64px 20px 0; }
				.fp-brand { font-size: 22px; }
				.fp-brand img { width: 170px; }
				.fp-theme-toggle { top: 16px; right: 16px; }
				.fp-title { font-size: 24px; }
				.fp-main { padding: 24px 16px 40px; }
				.fp-actions .form-select { flex: 1; }
			}
		</style>
		<script src="./js/color-toggle.js" defer></script>
	</head>
	<body onUnload="var s=document.getElementById('secret'); if(s){s.value='';}">
	<script>
			if (location.protocol != 'https:') {
				document.currentScript.insertAdjacentHTML('afterend', '<div class="fp-banner-wrap"><div class="alert alert-danger mb-0"><strong>Danger!</strong> This site is not being accessed over an encrypted connection. Do NOT input any sensitive information!</div></div>');
			}
			function copyText() {
				var textToCopy = document.getElementById("copy");
				textToCopy.select();
				if (navigator.clipboard && window.isSecureContext) {
					navigator.clipboard.writeText(textToCopy.value).catch(function() { document.execCommand("copy"); });
				} else {
					document.execCommand("copy");
				}
			}
	</script>
		<header>
			<div class="fp-header">
				<a class="fp-brand" href="./">
				<?php
					if ( $settings['site_logo'] != '' && $settings['display_logo'] == 'true' ) {
						echo '<img src="'. htmlspecialchars($settings['site_logo']) .'" alt="Logo">';
					}
				?>
				<?php
					if ( $settings['display_title'] == 'true') {
						echo htmlspecialchars($settings['site_title']);
					}
				?>
				</a>
				<div class="fp-theme-toggle">
					<!-- Sun/moon icons from Feather Icons (MIT) - https://feathericons.com -->
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" role="switch" id="themingSwitcher" />
					</div>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
				</div>
			</div>
			<?php
				if ( $settings['announcement'] != '' ) {
					echo '<div class="fp-banner-wrap"><div class="alert alert-warning mb-0"><strong>Announcement:</strong> ' . $settings['announcement'] . '</div></div>';
				}
			?>
		</header>
		<main class="fp-main">
