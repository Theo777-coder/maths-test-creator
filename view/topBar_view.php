<?php
require_once "../model/dataAccess.php";
require_once "../controller/topBar.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../CSS/test.css">
    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../AJAX/commonJS.js"></script>
</head>
<header>
    <nav class="navi" id="navigation">
        <div id="linkBar" class="hiddenWhenPrinted">
            <a class="navLinks" href="tests_view.php">Tests</a>
            <?php
            if (isset($message)) {

                echo ("<span class = 'attentionRedColor'>" . $message . "</span>");
            }
            ?>
            <div class="topcorner">
                <?php if (!isset($_SESSION["username"])) { ?>
                    <a class="navLinks" href="login_view.php">Log in</a>
                    <a class="navLinks" href="signUp_view.php">Sign up</a>
                <?php } else { ?>
                    <div id="profileIconTest" class="profileIcon">
                        <img id="profileIcon" alt="profile" src="../images/profileIcon.png">
                    </div>
                    <div id="profileContents" class="profileInfoBox">
                        <a href="profile_view.php"><?= $_SESSION["username"], "(", $_SESSION["score"], ")" ?></a>
                        <form class="logoutButton" method="POST">
                            <input class="logout" id="logOut" type="submit" name="logOut" value="Log Out">
                        </form>
                    </div>
                <?php }
                ?>
            </div>
        </div>
    </nav>
</header>


</html>