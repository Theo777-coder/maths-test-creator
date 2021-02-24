<?php

include_once 'connect.php';
include_once 'tests.php';
session_start();
//file_put_contents("php://stderr", "getHistory".session_id().PHP_EOL);
global $pdo;
$statement = $pdo->prepare("SELECT startTime,endTime,testTime,score,url FROM db_name.tests WHERE userid = 
(SELECT users.id FROM db_name.users WHERE users.username = ?) 
ORDER BY db_name.tests.startTime DESC limit 10 offset ?");
$start = $_GET["offset"];
$statement->bindValue(1, $_SESSION["username"],PDO::PARAM_STR);
$statement->bindValue(2, (int) $start,PDO::PARAM_INT);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_CLASS, "tests");
echo json_encode($results);

