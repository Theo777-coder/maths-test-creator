<?php
require_once "../model/user.php";
require_once "../model/dataAccess.php";

if (isset($_REQUEST["logOut"])) {
    unset($_SESSION['timer']);
    setcookie("timer", "", [
        'expires' => time() - 3600,
        //'secure' => true,
        //'httponly' => true,
        'path' => '/',
        'samesite' => 'Strict',
    ]);
    unset($_SESSION['score']);
    unset($_SESSION['username']);
    unset($_SESSION['wrongOrRight1']);
    unset($_SESSION['userAnswers']);
    unset($_SESSION['correctAnswers']);
    unset($_SESSION['questions']);
    regenerateSessionID();
}