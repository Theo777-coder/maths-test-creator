<?php
require_once "../controller/login.php";
require_once "../model/dataAccess.php";
require_once "../model/user.php";
require_once "links.php";
?>
<!doctype html>
<html>

    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../CSS/test.css">
        <script src="../AJAX/commonJS.js"></script>
    </head>


    <body>
        <div class="center">
            <h1>Login</h1>
            <div class="box boxDisplay">
                <?php if (empty($_SESSION["username"])) { ?>
                    <form method="POST">
                        <label class="required" for="username">Username</label><br>
                        <input class="normalBorder" type="text" name="username" id="username" placeholder="username"/><br>
                        <label class="required" for="username">Password</label><br>
                        <input class="normalBorder" type="password" name="password" id="password" placeholder="password" required/><br>
                        <input type="submit" name="logIn" value="Log In"/><br>
                    </form>
                    <?php
                }
                ?>

                <?php
                if (isset($results)):
                    ?>               
                    <p class="warning"><?= $results ?></p>
                    <?php
                endif;
                ?>
            </div>
        </div>

    </body>

</html>