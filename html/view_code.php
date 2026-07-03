<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div id="form-div">
			<fieldset>
				<h1 class="fp-title"><?php echo $settings['messages']['view_code_header'] ?></h1>
				<p class="fp-subtitle"><?php echo $settings['messages']['view_code_subheader'] ?></p>
				<input type="text" readonly id="copy" class="form-control" value="<?php echo htmlspecialchars($message, ENT_QUOTES) ?>"/>
				<div class="fp-actions">
					<button class="btn btn-primary" type="button" onclick="copyText()">Copy</button>
				</div>
			</fieldset>
		</div>
