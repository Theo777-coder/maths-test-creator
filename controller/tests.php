<?php

require_once "../model/user.php";
require_once "../view/tests_view.php";
require_once "../model/dataAccess.php";

if (isset($_REQUEST["logOut"])) {
    unset($_SESSION['score']);
    unset($_SESSION['username']);
    unset($_SESSION['wrongOrRight1']);
    unset($_SESSION['userAnswers']);
    unset($_SESSION['correctAnswers']);
    unset($_SESSION['questions']);
    setcookie("timer", "false");
} else if (isset($_REQUEST["createTests"])) {
    checkToken("createTests", $_REQUEST["token"]);
    unset($_SESSION["userAnswers"]);
    $_SESSION["decimalAccuracy"] = $_REQUEST["decimalAccuracy"];
    createQuestions($_REQUEST["noOfTerms"], $_REQUEST["magnitudeMin"], $_REQUEST["magnitudeMax"], $_REQUEST["topic"], $_REQUEST["seedID"]);
} else if (isset($_REQUEST["retryTest"])) {
    unset($_SESSION["userAnswers"]);
    unset($_SESSION["wrongOrRight1"]);
    unset($_SESSION["correctAnswers"]);
}
