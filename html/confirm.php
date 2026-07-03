<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div id="form-div" class="text-center">
			<fieldset>
				<h1 class="fp-title mb-4"><?php echo $settings['messages']['confirm_view_secret_header'] ?></h1>
				<form method="post" action="./">
					<input type="hidden" name="k" value="<?php echo htmlspecialchars($_GET['k']) ?>">
					<button type="submit" onclick="this.disabled=true;this.form.submit();" class="btn btn-primary"><?php echo $settings['messages']['confirm_view_secret_button'] ?></button>
				</form>
			</fieldset>
		</div>
