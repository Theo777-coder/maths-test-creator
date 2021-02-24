<?php
require_once "../model/user.php";
require_once "../view/signUp_view.php";
require_once "../model/dataAccess.php";
if (isset($_REQUEST["signUp"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $occupation = $_POST["Occupation"];
    $passwordRepeat = $_POST["passwordRepeat"];
    $results = signUpChecker($username, $password, $passwordRepeat, $occupation);
}
?>