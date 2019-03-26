<?php

require_once 'functions.php';
// $db = require_once 'config/db.php';

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");

$categories = [];
$content = '';

if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('404.php', ['error' => $error]);
    print("Ошибка подключения: ". mysqli_connect_error());
}

?>