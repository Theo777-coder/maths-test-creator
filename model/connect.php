<?php
$db = parse_url(getenv("DATABASE_URL"));

$pdo = new PDO("pgsql:" . sprintf(
    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
    $db["host"],
    $db["port"],
    $db["user"],
    $db["pass"],
    ltrim($db["path"], "/")
));

//localhost login
// $dsn = "pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=postgres";
// $pdo = new PDO($dsn);
?>
