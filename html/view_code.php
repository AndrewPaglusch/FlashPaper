<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div class="centered-div">
			<div id="form-div">
				<fieldset>
					<div>
						<label style="font-family: 'Proxima Nova', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $settings['messages']['view_code_header'] ?></label>
						<div style="font-style: italic">
							<label style="font-family: 'Proxima Nova', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $settings['messages']['view_code_subheader'] ?></label>
						</div>
						<div style="margin-top:10px">
							<input type="text" readonly id="copy" class="form-control" name="secret" style="margin-bottom: 15px" value="<?php echo $message ?>"/>
						</div>
						<div class="col">
							<button class="btn btn-primary" type="button" onclick="copyText()">Copy</button>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
