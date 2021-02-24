<?php

include_once 'connect.php';
include_once 'tests.php';
session_start();
if (isset($_SESSION["username"])) {
    global $pdo;
    $statement = $pdo->prepare("SELECT testTime FROM  db_name.tests WHERE  userID = (SELECT users.id FROM db_name.users WHERE users.username = ?)
    ORDER  BY id DESC LIMIT  1");
    $statement->bindValue(1, $_SESSION["username"], PDO::PARAM_STR);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_CLASS, "tests");
}
if (!isset($_SESSION["username"])) {
    $origin = date_create($_SESSION["testStartTime"]);
    $target = date_create($_SESSION["endTime"]);
    $interval = date_diff($origin, $target);
    echo json_encode($interval->format('%H:%M:%S'));
}
