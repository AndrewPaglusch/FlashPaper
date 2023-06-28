<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div id="form-div">
			<fieldset>
				<div>
					<img style="width: 120px;height:auto;" src="./assets/Ghost_Secret_logo_full.png">
					<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $settings['messages']['view_secret_header'] ?></label>
					<div style="font-style: italic">
						<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $settings['messages']['view_secret_subheader'] ?></label>
					</div>
					<div style="margin-top:10px">
						<textarea readonly id="copy" class="form-control" name="secret" rows="8" style="resize: vertical; margin-bottom: 15px"><?php echo $message ?></textarea>
					</div>
					<div class="col">
						<button class="btn btn-primary" type="button" onclick="copyText()">Copy</button>
					</div>
				</div>
			</fieldset>
		</div>
