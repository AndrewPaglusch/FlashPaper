<?php #defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div class="centered-div">
			<div id="form-div">
				<form class="form-horizontal" action="" method="POST">
					<fieldset>
						<div>
							<label style="font-family: 'Proxima Nova', arial, serif; color:#fafafa; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $settings['messages']['submit_secret_header']; ?></label>
							<div style="font-style: italic">
								<label style="font-family: 'Proxima Nova', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $settings['messages']['submit_secret_subheader'] ?></label>
							</div>
							<div style="margin-top:10px; margin-bottom: 15px;">
								<textarea class="form-control" id="secret" name="secret" rows="8" maxlength="<?php echo $settings['max_secret_length'] ?>" style="resize: vertical;" placeholder="Secret text..." autofocus><?php echo $template_text ?></textarea>
							</div>
						</div>
						<div style="margin-bottom:10px;">
							<div class="centered-div center-slider">
								<label for="expire-days" class="slider-desc">Days Before Expiration </label>
								<input type="range" min="1" max="100" value="50" class="slider" id="expire-days" oninput="this.nextElementSibling.value = this.value">
								<output class="slider-value">50</output><span class="slider-after">Days</span>
							</div>
							<div class="centered-div center-slider">
								<label for="view-count" class="slider-desc">Views Before Destruction </label>
								<input type="range" min="1" max="100" value="50" class="slider" id="view-count" oninput="this.nextElementSibling.value = this.value">
								<output class="slider-value">50</output><span class="slider-after">Views</span>
							</div>
						</div>
						
						<div class="centered-div">
							<div class="form-group row float-start">

								<div class="col">
									<button name="submit" type="submit" class="btn btn-primary"><img class="btn-img" src="./assets/padlock-black-icon.png"><?php echo $settings['messages']['submit_secret_button'] ?></button>
								</div>
								<div class="col">
									<button type="button" onclick="document.getElementById('secret').value = ''" class="btn btn-primary"><img class="btn-img" src="./assets/no-data-icon.png">Clear Message</button>
								</div>
								<div class="col">
									<select id="select" name="select" class="form-select" onChange="window.location.href=this.value">
										<option value="" selected disabled hidden>Select Template</option>
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
						</div>
					</fieldset>
				</form>
			</div>

		</div>

		<br>
