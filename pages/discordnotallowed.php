<html>
    <head>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto+Mono:300,500');

        html, body {
            width: 100%;
            height: 100%;
        }

        body {
            background-image: url(../images/discordauthfailbackground.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 100vh;
            min-width: 100vw;
            font-family: "Roboto Mono", "Liberation Mono", Consolas, monospace;
            color: rgba(255,255,255,.87);
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .container,
        .container > .row,
        .container > .row > div {
            height: 100%;
        }

        #countUp {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            font-weight: 300;
            text-align: center;
        }
    </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="xs-12 md-6 mx-auto">
                    <div id="countUp">
                        <div class="text">You are not allowed or failed the authentication step. This means you could not be verified. Discord authentication failed for your account</div>
                        <div class="text">Click this to log out and retry.</div>
                        <div class="text"><a href='../?discordlogout=true' style="color:#ff0000;">Log out</a>.</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>



<?php

echo " :( <br>";
echo ": <br>";
echo "";

?>