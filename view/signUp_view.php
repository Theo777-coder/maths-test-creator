<?php
require_once "../model/dataAccess.php";
require_once "../model/user.php";
require_once "../controller/signUp.php";
require_once "links.php";
?>

<html>
    <head>
        <title>Sign up</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../CSS/test.css">
        <script src="../AJAX/commonJS.js"></script>
    </head>


    <body>
        <div class="center">
            <h1 class="center">Sign up</h1>
            <div class="box boxDisplay">
                <?php if (empty($_SESSION["username"])) { ?>
                    <form method="POST">
                        <label class="required" for="username">Username</label><br>
                        <input class="normalBorder" type="text" name="username" id="username" placeholder="username"/><br>
                        <label class="required" for="password">password</label><br>
                        <input class="normalBorder" type="password" name="password" id="password" placeholder="password" required/><br>
                        <label class="required" for="passwordRepeat">Repeat password</label><br>
                        <input class="normalBorder" type="password" name="passwordRepeat" id ="passwordRepeat" placeholder="password again" required/><br>
                        <label for="Occupation">Occupation</label><br>
                        <select class="normalBorder" name="Occupation" id = "Occupation">
                            <option value="">choose one</option>
                            <option value="teacher">teacher</option>
                            <option value="student">student</option>
                        </select><br>
                        <input type="submit" name="signUp" value="Sign Up"/><br>
                    </form>
                    <?php
                }
                ?>

                <?php
                if (isset($results) && $results == "success"):
                    ?>               
                    <p class="success"><?= $results ?></p>
                    <?php
                elseif (isset($results)):
                    ?>               
                    <p class="warning"><?= $results ?></p>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </body>

</html>