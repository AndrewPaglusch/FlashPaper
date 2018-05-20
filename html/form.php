<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
    <body onUnload="document.getElementById('secret').value = ''">
        <div id="form-div">
            <form class="form-horizontal" action=index.php method=POST>
                <fieldset>
                    <div>
                        <label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $message_title; ?></label>
                        <div style="font-style: italic">
                            <label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $message_subtitle; ?></label>
                        </div>
                        <div style="margin-top:10px">
                            <textarea class="form-control" id="secret" name="secret" placeholder="Secret text..."><?php echo $template_text ?></textarea>
                        </div>
                    </div>
					<div class="clearfix visible-xs"></div>
                    <div class="form-group row float-right" style='padding-top: 3%'>
                      <!-- <label class="col-4 col-form-label" for="select">Select Template</label> -->
					<div class="w-100"></div>
					<div class="col">
                         <select id="select" name="select" class="custom-select" onChange="window.location.href=this.value">
							<option value="" selected disabled hidden>-- Select Template</option>
                            <option value="/">No Template</option>
                            <option value="?t=cc">Credit Card</option>
                            <option value="?t=creds">Credentials</option>
                         </select>
                       </div>
                     </div> 
					<div class="w-100"></div>
                     <div class="form-group row float-left" style='padding-top: 3%'>
                        <div class="col">
                           <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                     </div>
                </fieldset>
            </form>
        </div>
