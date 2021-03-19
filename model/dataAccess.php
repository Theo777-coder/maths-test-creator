<?php

require_once "connect.php";
require_once "user.php";
require_once "tests.php";


session_start();

function login($username, $password)
{
    global $pdo;
    $statement = $pdo->prepare("SELECT * FROM db_name.users WHERE username = ?");
    $statement->execute([$username]);
    $statement->setFetchMode(PDO::FETCH_CLASS, 'User');
    $results = $statement->fetch();

    if (empty($results)) {
        password_verify("", "DUMMY_HASH");
        return "Incorrect username or password!";
    }
    $isPasswordCorrect = password_verify($password, $results->password);
    if (empty($isPasswordCorrect)) {
        password_verify("", "DUMMY_HASH");
        return "Incorrect username or password!";
    } else {
        $_SESSION["username"] = $results->username;
        $_SESSION["score"] = $results->score;
        $_SESSION["token"] = bin2hex(openssl_random_pseudo_bytes(128));
        $_SESSION['LAST_ACTIVITY'] = time();
        redirect("../view/tests_view.php");
    }
}

function checkToken($form, $token)
{
    if (isset($_SESSION["username"])) {
        $calc = hash_hmac('sha256', $form, $_SESSION['token']);
        if (!hash_equals($token, $calc)) {
            die("Unique token not matching");
        }
    }
}

function getTestsHistory()
{
    global $pdo;
    $statement = $pdo->prepare("select * from db_name.tests Where userID = (SELECT users.id FROM db_name.users WHERE users.username = ?) limit 10");
    $statement->execute([$_SESSION["username"]]);
    $results = $statement->fetchAll(PDO::FETCH_CLASS, "tests");
    return $results;
}

function getTestTime($startTime, $endTime)
{
    $date1 = new DateTime($startTime);
    $date2 = new DateTime($endTime);
    $interval = $date1->diff($date2);
    return $interval->format('%H:%M:%S');
}

function delay($input, $secret_key)
{
    $hash = crc32(serialize($secret_key . $input . $secret_key));
    time_nanosleep(0, abs($hash % 100000));
}

function redirect($url)
{
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    die();
}

function signUpChecker($username, $password, $passwordRepeat, $occupation)
{
    $result = "";
    if (nameChecker($username) != 0) {
        return "Name taken";
    } else if ($password == $passwordRepeat) {
        $hashToStoreInDb = password_hash($password, PASSWORD_DEFAULT);
        $result = signUp($username, $hashToStoreInDb, $occupation);
    } else {
        $result = "Passwords are not the same";
    }
    return $result;
}

function getUserData($username)
{
    global $pdo;
    $statement = $pdo->prepare("SELECT * FROM db_name.users WHERE username = ?");
    $statement->execute([$username]);
    $statement->setFetchMode(PDO::FETCH_CLASS, 'User');
    $results = $statement->fetch();
    return $results;
}

function symbolArray($topics)
{
    $topicArray = [];
    foreach ($topics as &$value) {
        array_push($topicArray, symbolMap($value));
    }
    return $topicArray;
}

function createQuestions($noOfTerms, $magnitudeMin, $magnitudeMax, $topics, $seed)
{
    srand($seed);
    $singleQuestion = "";
    $tempQuestions = [];
    setcookie("timer", "true", [
        'expires' => time() + 86400,
        //'secure' => true,
        //'httponly' => true,
        'path' => '/',
        'samesite' => 'Strict',
    ]);

    $topicArray = symbolArray($topics);
    for ($y = 0; $y < 10; $y++) {
        for ($x = 0; $x < $noOfTerms - 1; $x++) {
            if ($x == 0) {
                $singleQuestion = rand($magnitudeMin, $magnitudeMax);
            }
            $singleQuestion = $singleQuestion . $topicArray[rand(0, count($topicArray) - 1)] . rand($magnitudeMin, $magnitudeMax);
        }
        array_push($tempQuestions, $singleQuestion);
    }
    srand();
    $_SESSION["timer"] = "true";
    $_SESSION["testStartTime"] = date("Y-m-d H:i:s");
    $_SESSION["questions"] = $tempQuestions;
    unset($_SESSION["correctAnswers"]);
}

function markTest($userAnswers)
{
    $correctAnswers = [];
    $wrongOrRight = [];
    for ($y = 0; $y < 10; $y++) {
        $tempAnswer = eval('return ' . $_SESSION["questions"][$y] . ';');
        array_push($correctAnswers, round($tempAnswer, $_SESSION["decimalAccuracy"]));
        if ((string) $correctAnswers[$y] === $userAnswers[$y]) {
            array_push($wrongOrRight, 1);
        } else {
            array_push($wrongOrRight, 0);
        }
    }
    setcookie("timer", "done", [
        'expires' => time() + 86400,
        //'secure' => true,
        //'httponly' => true,
        'path' => '/',
        'samesite' => 'Strict',
    ]);
    $_SESSION["timer"] = "done";
    $_SESSION["userAnswers"] = $userAnswers;
    $_SESSION["correctAnswers"] = $correctAnswers;
    $_SESSION["wrongOrRight1"] = $wrongOrRight;
    $score = array_count_values($_SESSION["wrongOrRight1"]);
    $_SESSION["endTime"] = date("Y-m-d H:i:s");
    if (isset($_SESSION["score"])) {
        $_SESSION["score"] = $_SESSION["score"] + (int) $score[1];
        addScore($_SESSION["score"], $_SESSION["username"]);
    }
    return $userAnswers;
}

function checkActivity()
{
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        setcookie("timer", "", [
            'expires' => time() - 3600,
            //'secure' => true,
            //'httponly' => true,
            'path' => '/',
            'samesite' => 'Strict',
        ]);
        unset($_SESSION['timer']);
        session_unset();
        session_destroy();
        return (1);
        exit();
        // redirect($_SERVER['PHP_SELF']);
    }
    if (isset($_SESSION["LAST_ACTIVITY"])) {
        $_SESSION["LAST_ACTIVITY"] = time();
    }
}

function regenerateSessionID()
{
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }
}

function nameChecker($username)
{
    $output = 0;
    global $pdo;
    $statement = $pdo->prepare("SELECT 1 FROM db_name.users WHERE username = ?");
    $statement->execute([$username]);
    $result = $statement->fetchAll(PDO::FETCH_CLASS, "user");
    if (!empty($result)) {
        $output = 1;
    }
    return $output;
}

function addScore($score, $username)
{
    global $pdo;
    $statement = $pdo->prepare("UPDATE db_name.users SET score = ? WHERE username = ?");
    $statement->execute([$score, $username]);
}

function signUp($username, $password, $occupation)
{
    global $pdo;
    $statement = $pdo->prepare("INSERT INTO db_name.users (username, password, occupation) VALUES (?, ?, ?)");
    $statement->execute([$username, $password, $occupation]);
    return "success";
}

function symbolMap($topic)
{
    $myhashmap = array();
    $myhashmap["addition"] = "+";
    $myhashmap["subtraction"] = "-";
    $myhashmap["multiplication"] = "*";
    $myhashmap["division"] = "/";
    return $myhashmap[$topic];
}
