<?php #defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div id="form-div">
			<form class="form-horizontal" action="" method="POST">
				<fieldset>
					<div>
						<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $settings['messages']['submit_secret_header']; ?></label>
						<div style="font-style: italic">
							<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $settings['messages']['submit_secret_subheader'] ?></label>
						</div>
						<div style="margin-top:10px">
							<textarea class="form-control" id="secret" name="secret" rows="8" maxlength="<?php echo $settings['max_secret_length'] ?>" style="resize: vertical;" placeholder="Secret text..."><?php echo $template_text ?></textarea>
						</div>
					</div>
					<div class="form-group row float-end" style='padding-top: 3%'>
						<div class="col">
							<select id="select" name="select" class="form-select" onChange="window.location.href=this.value">
								<option value="" selected disabled hidden>-- Select Template</option>
								<option value="./">No Template</option>
								<?php
									$templates = glob('templates/*.txt');
									foreach ($templates as $t) {
										$filename = basename($t, '.txt');
										$url_filename = urlencode($filename);
										echo "<option value=\"?t={$url_filename}\">{$filename}</option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row float-start" style='padding-top: 3%'>
						<div class="col">
							<button name="submit" type="submit" class="btn btn-primary"><?php echo $settings['messages']['submit_secret_button'] ?></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>

		<br>
