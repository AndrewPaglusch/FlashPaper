<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
		<div style="width: 60%" id="form-div" class="mx-auto">
			<fieldset style="text-align: center">
				<div class="form-group row float-middle">
					<div class="col">
						<label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 15px; font-size: 30px; font-weight: bold; padding-bottom: 1%">View this secret?</label>
                        <br />
						<form method="post" action="/">
							<input type="hidden" name="k" value="<?php echo $_GET['k'] ?>">
							<button type="submit" class="btn btn-primary w-20 mx-auto">View Secret</button>
						</form>
					</div>
				</div>
			</fieldset>
		</div>
