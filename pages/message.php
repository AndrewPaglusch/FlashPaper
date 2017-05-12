<html>
    <head>
        <title>Password</title>
        <style>
            #form-div {
                background-color:rgba(72,72,72,0.1);
                padding-left:35px;
                padding-right:35px;
                padding-top:35px;
                padding-bottom:50px;

                margin:30px;
                -moz-border-radius: 7px;
                -webkit-border-radius: 7px;
            }
            textarea {
                width: 100%;
                height: 150px;
                padding: 12px 20px;
                box-sizing: border-box;
                border: 2px solid #ccc;
                border-radius: 4px;
                background-color: #f8f8f8;
                resize: none;
            }
            fieldset {
                margin:10px;
                border: 0;
            }
        </style>
    </head>

    <body>
        <div id="form-div">
            <fieldset>
                <div>
                    <label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 30px; font-weight: bold;"><?php echo $message_title; ?></label>
                    <div style="font-style: italic">
                        <label style="font-family: 'Enriqueta', arial, serif; line-height: 1.25; margin: 0 0 10px; font-size: 15px; font-weight: bold;"><?php echo $message_subtitle; ?></label>
                    </div>
                    <div style="margin-top:10px">                     
                        <textarea readonly class="form-control" name="secret"><?php echo $message ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
    </body>
</html>

