<?php defined('_DIRECT_ACCESS_CHECK') or exit(); ?>
    <body>
        <div id="form-div">
            <form class="form-horizontal" action=index.php method=POST>
                <fieldset>
                    <div>
                        <label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;">Self-Destructing Message</label>
                        <div style="margin-top:10px">
                            <textarea class="form-control" name="secret" placeholder="Secret text..."><?php echo $template_text ?></textarea>
                        </div>
                    </div>
                    <div style="margin-top: 1%; width: 100%">
                        <!-- <div style='float: right; padding-right:2%;'><a style='text-decoration: none; font-size: 12px; font-style: italic; color:rgba(72,72,72,0.8);' href='request.php'>Create A Secret Request</a></div>-->
                        <div style='float: right; padding-right:2%;'>
                           <select onChange="window.location.href=this.value">
                                <option value="" selected disabled hidden>-- Select Template</option>
                                <option value="/">No Template</option>
                                <option value="?t=cc">Credit Card</option>
                                <option value="?t=creds">Credentials</option>
                           </select></div>
                        <div style='float: left;'>
                            <input type="submit" name="submit" value="Submit">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
