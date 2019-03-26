<?php
require 'mysql_helper.php';
require 'functions.php';
require_once 'init.php';

session_start();

if (!isset($_SESSION['user'])){
    header('HTTP/1.0 403 Forbidden');
    $error = 'Пожалуйста, войдите в свой аккаунт.';
    $code = '403';
    $page_content = include_template('404.php', ['error' => $error, 'code' => $code]);
    //  exit();
}
else {

    if ($con) { // connection established
        $sql = "SELECT id, name FROM categories";
        $result = mysqli_query($con, $sql);
        if (!$result) { // categories for dropdown list NOT fetched
            $error = mysqli_error($con);
            $page_content = include_template('404.php', ['error' => $error]);
        }
        else { // categories for dropdown list ARE fetched
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

            if ($_SERVER ['REQUEST_METHOD'] == 'POST') { // we got POST request, start to process data
                $lot = $_POST['lot'];

                $required = ['name', 'category', 'description', 'st_price', 'bet_step', 'end_date'];
                $numeric = ['st_price', 'bet_step'];
                $dict = ['name' => 'Имя', 'category' => 'Категория', 'description' => 'Описание', 'file' => 'Файл',
                    'st_price' => 'Начальная цена', 'bet_step' => 'Шаг ставки', 'end_date' => 'Дата окончания'];
                $errors = [];

                foreach ($numeric as $key){
                    if (!is_numeric($lot[$key])){
                        $errors[$key] = 'Пожалуйста, введите число';
                    }
                }

                foreach ($required as $key) { // fill ERRORS array with empty fields
                    if (empty($lot[$key])) {
                        $errors[$key] = 'Пожалуйста, заполните это поле';
                    }
                }
				
			
			if (!is_numeric($lot['st_price']) || $lot['st_price'] <= 0 || $lot['st_price'] != round($lot['st_price'], 0)){
					$errors['st_price'] = 'Пожалуйста, введите целое положительное число :)';		
				}
				
			if (!is_numeric($lot['bet_step']) || $lot['bet_step'] <= 0 || $lot['bet_step'] != round($lot['bet_step'], 0)){
					$errors['bet_step'] = 'Пожалуйста, введите целое положительное число :)';		
				}
				 
				
				

		$unixtime = strtotime($lot['end_date']);
		if ($unixtime) {
			if ($unixtime < strtotime('tomorrow')) {
				$errors['end_date'] = 'Указанная дата должна быть больше текущей даты хотя бы на один день';
			}
		} else {
			$errors['end_date'] = 'Введите дату в формате ДД.ММ.ГГГГ';
		}

                if (isset($_FILES['new_image']['name']) && !empty($_FILES['new_image']['tmp_name'])) { // check if file name exists (YES) - isset($_FILES['new_image']['name'])
                    $tmp_name = $_FILES['new_image']['tmp_name'];
                    $path = $_FILES ['new_image']['name'];

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $file_type = finfo_file($finfo, $tmp_name);

                    if ($file_type !== "image/png" && $file_type !== "image/jpg" && $file_type !== "image/jpeg") { // file type IS NOT image
                        $errors['file'] = 'Пожалуйста, загрузите картинку :)';
                    }
                    else { // file type IS image
						$dir = 'lots_img';
						
						if (!file_exists($dir)){
							mkdir($dir, 0777, false);
						}
						move_uploaded_file($tmp_name, $dir . '/' . $path);
						$lot['path'] = $dir . '/' . $path;
                    }

                } else { // file is not uploaded
                    $errors['file'] = 'Вы не загрузили файл';
                }

                if (count($errors)) { // if there ARE errors
                    $page_content = include_template('tmpl_add.php', ['_FILES' => $_FILES, 'lot' => $lot, 'errors' => $errors, 'dict' => $dict, 'categories' => $categories]);
                } else { // there are NO errors
                    $sql = 'INSERT INTO lots (created_date, title, description, image, start_price, end_date, bet_step, author, category) 
                    VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ? )';
                    $stmt = db_get_prepare_stmt($con, $sql, [$lot['name'], $lot['description'], $lot['path'], $lot['st_price'], $lot['end_date'], $lot['bet_step'], $_SESSION['user']['id'], $lot['category']]);
                    $result = mysqli_stmt_execute($stmt);

                    if ($result) { // successfully inserted the lot
                        $lot_id = mysqli_insert_id($con);

                        header("Location: lot.php?id=" . $lot_id);
                    } else { // problem while insert
                        $content = include_template('404.php', ['error' => mysqli_error($con)]);
                    }
                }
            }
            else { // came first time, fresh page
                $page_content = include_template('tmpl_add.php', ['categories' => $categories]);
            }
        }
    }
}


$user_data = $_SESSION['user'];
$layout_content = include_template('layout.php',
    ['content' => $page_content,
        'categories' => $categories,
        'title' => 'Добавить лот',
        'user_data' => $user_data]);

print($layout_content);
?>