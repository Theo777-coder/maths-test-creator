<?php
require_once "../model/user.php";
require_once "../view/login_view.php";
require_once "../model/dataAccess.php";


if (checkActivity()){
    $message = "You have been logged off due to inactivity!";
    return $message;
}
regenerateSessionID();

    if (isset($_REQUEST["logIn"])) {
        isset($_REQUEST["keepLoggedIn"]) ? $_SESSION["timeAddition"] = 100000 : $_SESSION["timeAddition"] = 0;
        $results = login($_POST["username"], $_POST["password"]);
    }
