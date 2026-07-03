<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div id="form-div">
			<fieldset>
				<h1 class="fp-title"><?php echo $settings['messages']['view_secret_header'] ?></h1>
				<p class="fp-subtitle"><?php echo $settings['messages']['view_secret_subheader'] ?></p>
				<textarea readonly id="copy" class="form-control" rows="8"><?php echo $message ?></textarea>
				<div class="fp-actions">
					<button class="btn btn-primary" type="button" onclick="copyText()">Copy</button>
				</div>
			</fieldset>
		</div>
