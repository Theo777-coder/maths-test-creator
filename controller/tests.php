<?php

require_once "../model/user.php";
require_once "../view/tests_view.php";
require_once "../model/dataAccess.php";


if (checkActivity()){
    $message = "You have been logged off due to inactivity!";
    return $message;
}
regenerateSessionID();

if (isset($_REQUEST["createTests"])) {
    createQuestions($_REQUEST["noOfTerms"], $_REQUEST["magnitudeMin"], $_REQUEST["magnitudeMax"], $_REQUEST["topic"], $_REQUEST["seedID"]);
    checkToken("createTests", $_REQUEST["token"]);
    unset($_SESSION["userAnswers"]);
    $_SESSION["decimalAccuracy"] = $_REQUEST["decimalAccuracy"];
} else if (isset($_REQUEST["retryTest"])) {
    unset($_SESSION["userAnswers"]);
    unset($_SESSION["wrongOrRight1"]);
    unset($_SESSION["correctAnswers"]);
}
