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

    $tpl_data = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form = $_POST['signup'];
        $errors = [];
        $req_fields = ['email', 'password', 'name', 'message'];
        $dict = ['email' => 'Email', 'password' => 'Пароль', 'name' => 'Имя', 'message' => 'Контактные данные', 'avatar' => 'Аватар', 'duplicate' => 'Email'];

        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email должен быть корректным';
        }
        foreach ($req_fields as $field) {
            if (empty($form[$field])) {
                $errors[$field] = "Не заполнено поле \"" . $dict[$field] ."\"";
            }

        }
        if (isset($_FILES['avatar']['name']) && !empty($_FILES['avatar']['tmp_name'])) { // check if file name exists (YES) - isset($_FILES['avatar']['name'])
            $tmp_name = $_FILES['avatar']['tmp_name'];
            $path = $_FILES ['avatar']['name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);

            if ($file_type !== "image/png" && $file_type !== "image/jpg" && $file_type !== "image/jpeg") { // file type IS NOT image
                $errors['avatar'] = 'Пожалуйста, загрузите картинку :)';
            } else { // file type IS image
			
				$dir = 'avatar_img';
						
				if (!file_exists($dir)){
					mkdir($dir, 0777, false);
				}
				move_uploaded_file($tmp_name, $dir . '/' . $path);
				$form['path'] = $dir . '/' . $path;

            }
        }


        if (empty($errors)) { // if there ARE NO errors
            $email = mysqli_real_escape_string($con, $form['email']);
            $sql = "SELECT id FROM users WHERE email = '$email'";
            $res = mysqli_query($con, $sql);

            if (mysqli_num_rows($res) > 0) {
                $errors['duplicate'] = 'Пользователь с этим email уже зарегистрирован';
            }
            else {
                $form['path'] = isset($form['path']) ? $form['path'] : "";
                $password = password_hash($form['password'], PASSWORD_DEFAULT);
                $sql = 'INSERT INTO users (reg_date, email, name, password, contacts, avatar) VALUES (NOW(), ?, ?, ?, ?, ?)';
                $stmt = db_get_prepare_stmt($con, $sql, [$form['email'], $form['name'], $password, $form['message'], $form['path']]);
                $res = mysqli_stmt_execute($stmt);
            }

            if ($res && empty($errors)) {
                header("Location: /login.php");
                exit();
            }
        }

        $tpl_data['errors'] = $errors;
        $tpl_data['values'] = $form;
        $tpl_data['dict'] = $dict;

    }
    else {
        if (isset($_SESSION['user'])) {
            header("Location: /");
            exit();
        }
    }
    $page_content = include_template('tmpl_sign-up.php', $tpl_data);
}

$user_data = $_SESSION['user'];

$layout_content = include_template('layout.php',
    ['content' => $page_content,
        'categories' => $categories,
        'title' => 'Регистрация',
        'user_data' => $user_data]);

print($layout_content);

?>