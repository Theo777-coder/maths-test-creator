<?php
require_once "../model/dataAccess.php";
require_once "../model/user.php";
require_once "../controller/profile.php";
require_once "topBar_view.php";
?>
<html>

    <head>
        <title>Profile information</title>
        <link rel="stylesheet" href="../CSS/test.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="../AJAX/profile.js" type="text/javascript"></script>
    </head>


    <body>
        <h1>Profile</h1>
        <div class="outerDIv">
            <?php
            if (isset($_SESSION["username"])):
                $userData = getUserData($_SESSION["username"]);
                ?>               
                <p>Welcome <b id="usernameCurrent"><?= $userData->username ?></b>!</p>
                <p>Your score is <b><?= $userData->score ?></b>!</p>
                <p>You registered <b><?= $userData->RegisterDate ?></b>.</p>
                <table id="historyTable">
                    <tr>
                        <th>#</th>
                        <th>time taken</th>
                        <th>score</th>
                        <th>url</th>
                    </tr>
                </table> 
                <button id="loadHistory">load more</button>
                <!--                <button id="updateInfo">Update info</button>
                                <div>
                                    <h2>Update Info</h2>
                                    <label for="username">Username</label><br>
                                    <input class="normalBorder" type="text" name="username" id="username" placeholder="username" /><br>
                                </div>-->
            <?php else: ?>
                <p>It seemed you are either not <a href="login_view.php">signed in</a> or don't have an account. Sign up <a href="signUp_view.php">here</a></p>
            <?php endif; ?>
        </div>
    </body>

</html>