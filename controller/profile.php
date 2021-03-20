<?php

require_once "../model/user.php";
require_once "../view/profile_view.php";
require_once "../model/dataAccess.php";

if (checkActivity()) {
    $message = "You have been logged off due to inactivity!";
    return $message;
}

//$userData = getUserData($_SESSION["username"]);
