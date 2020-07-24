<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div id="form-div">
			<fieldset>
				<div>
					<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $settings['messages']['view_secret_header'] ?></label>
					<div style="font-style: italic">
						<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $settings['messages']['view_secret_subheader'] ?></label>
					</div>
					<div style="margin-top:10px">
						<textarea readonly class="form-control" name="secret" rows="8" style="resize: vertical;"><?php echo $message ?></textarea>
					</div>
				</div>
			</fieldset>
		</div>
