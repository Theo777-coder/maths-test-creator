<?php
$endTime = date("Y-m-d H:i:s");
include_once 'connect.php';
include_once 'dataAccess.php';
//session_start();
$testURL = $_POST["testURL"];
global $pdo;
$date1 = new DateTime($_SESSION["testStartTime"]);
$date2 = new DateTime($endTime);
$interval = $date1->diff($date2);
markTest($_POST["testAnswers"]);
$score = array_count_values($_SESSION["wrongOrRight1"]);
//$PDO->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
$statement = $pdo->prepare("INSERT INTO db_name.tests (userID, startTime, endTime, testTime, score, url) VALUES "
        . "((SELECT users.id FROM db_name.users WHERE username = ?), ?, ?, ?, ?, ?)");
$statement->execute([$_SESSION["username"], $_SESSION["testStartTime"], $endTime, $interval->format('%H:%M:%S'), (int) $score[1], $testURL]);
$t = 'INSERT INTO tests (userID, startTime, endTime, testTime, score, url) VALUES ((SELECT users.id FROM users WHERE users.username = '.$_SESSION["username"].'), '.$_SESSION["testStartTime"].', '.$endTime.', '.$interval->format('%H:%M:%S').', '.(int) $score[1].', '.$testURL.')';