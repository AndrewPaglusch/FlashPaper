<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div id="form-div">
			<form action="" method="POST">
				<fieldset>
					<h1 class="fp-title"><?php echo $settings['messages']['submit_secret_header']; ?></h1>
					<p class="fp-subtitle"><?php echo $settings['messages']['submit_secret_subheader'] ?></p>
					<textarea class="form-control" id="secret" name="secret" rows="8" maxlength="<?php echo $settings['max_secret_length'] ?>" placeholder="Secret text..." autofocus><?php echo htmlspecialchars($template_text, ENT_QUOTES) ?></textarea>
					<div class="fp-actions">
						<button name="submit" type="submit" class="btn btn-primary"><?php echo $settings['messages']['submit_secret_button'] ?></button>
						<select class="form-select" onChange="window.location.href=this.value" aria-label="Select template">
							<option value="" selected disabled hidden>-- Select Template</option>
							<option value="./">No Template</option>
							<?php
								$templates = glob('templates/*.txt');
								foreach ($templates as $t) {
									$filename = basename($t, '.txt');
									$url_filename = urlencode($filename);
									echo "<option value=\"?t={$url_filename}\">" . htmlspecialchars($filename) . "</option>";
								}
							?>
						</select>
					</div>
				</fieldset>
			</form>
		</div>
