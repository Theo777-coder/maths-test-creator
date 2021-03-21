<?php
require_once "../controller/login.php";
require_once "../model/dataAccess.php";
require_once "../model/user.php";
require_once "topBar_view.php";
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
                    <input class="normalBorder" type="text" name="username" id="username" placeholder="username" /><br>
                    <label class="required" for="username">Password</label><br>
                    <input class="normalBorder" type="password" name="password" id="password" placeholder="password" required /><br>
                    <br>
                    <div class="tooltip">
                        <label class="container">Keep me logged in
                            <input type="checkbox" name="keepLoggedIn" id="keepLoggedIn" value="Keep me logged in">
                            <span class="checkmark checkmarkLogInPos"></span>
                        </label>
                        <span class="tooltiptext tooltipLogInPos">Won't log you out after 30 minutes of inactivity. Don't tick on public computers such as at school/library.</span>
                    </div>
                    <br>
                    <input type="submit" name="logIn" value="Log In" /><br>
                </form>
            <?php
            }
            ?>

            <?php
            if (isset($results)) :
            ?>
                <p class="warning"><?= $results ?></p>
            <?php
            endif;
            ?>
        </div>
    </div>

</body>

</html>