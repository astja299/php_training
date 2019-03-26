<?php

require 'functions.php';
require_once 'init.php';

session_start();

if ($con) {
    // выполнение запросов
    $sql = "SELECT id, name FROM categories";
    $result = mysqli_query($con, $sql);


    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $page_content = include_template('404.php', ['error' => $error]);
        print("Ошибка: " . $error);
    }

    $sql = "SELECT lots.title, lots.id, lots.start_price, lots.image,  categories.name, lots.end_date
            FROM lots INNER JOIN categories ON lots.category = categories.id
            GROUP BY lots.id
            ORDER BY lots.created_date DESC ";

    $result = mysqli_query($con, $sql);
    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $user_data = $_SESSION['user'];
    $page_content = include_template('tmpl_index.php', ['categories' => $categories, 'lots' => $lots]);
    $hide_categories_in_header = true;

    $layout_content = include_template('layout.php',
        ['content' => $page_content,
            'categories' => $categories,
            'title' => 'Главная',
            'hide_categories_in_header' => $hide_categories_in_header,
            'user_data' => $user_data]);

    print($layout_content);
}
?>