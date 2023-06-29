<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div class="centered-div">
			<div id="form-div">
				<fieldset>
					<div>
						<label style="font-family: 'Proxima Nova', arial, serif; color:#fafafa; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $settings['messages']['view_secret_header'] ?></label>
						<div style="font-style: italic">
							<label style="font-family: 'Proxima Nova', arial, serif; color:#fafafa; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $view_message ?></label>
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
		</div>
