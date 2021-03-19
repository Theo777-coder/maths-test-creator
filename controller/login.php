<?php
require_once "../model/user.php";
require_once "../view/login_view.php";
require_once "../model/dataAccess.php";


if (checkActivity()){
    $message = "You have been logged off due to inactivity!";
    return $message;
}
regenerateSessionID();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (isset($_REQUEST["logIn"])) {
        $results = login($username, $password);
    }
}
?>