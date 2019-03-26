<?php
require_once 'init.php';
require_once 'functions.php';
require 'mysql_helper.php';

session_start();

if ($con) { // connection established
    $sql = "SELECT id, name FROM categories";
    $result = mysqli_query($con, $sql);

    if ($result) { // categories for layout list  fetched
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form = $_POST;
        $required = ['email', 'password'];
        $errors = [];
        foreach ($required as $field) {
            if (empty($form[$field])) {
                $errors[$field] = 'Это поле надо заполнить';
            }
        }
        if (!empty($form['email']) && !empty($form['password']) ) {
            $email = mysqli_real_escape_string($con, $form['email']);
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $res = mysqli_query($con, $sql);

            $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

            if (!count($errors) and $user) {
                if (password_verify($form['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Неверный пароль';
                }
            } else {
                $errors['email'] = 'Такой пользователь не найден';
            }
        }


        if (count($errors)) {
            $page_content = include_template('tmpl_login.php', ['form' => $form, 'errors' => $errors]);
        }
        else {
            header("Location: /");
            exit();
        }
    }
    else {
        if (isset($_SESSION['user'])) {
            header("Location: /");
            exit();
        }
        else {
            $page_content = include_template('tmpl_login.php', []);
        }
    }
}

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'categories' => $categories,
    'title'      => 'Вход'
]);

print($layout_content);