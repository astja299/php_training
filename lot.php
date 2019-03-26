<?php
require 'mysql_helper.php';
require 'functions.php';
require_once 'init.php';

session_start();

if ($con) {
    $sql = "SELECT id, name FROM categories";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        $page_content = include_template('404.php', ['error' => $error]);
    } else {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (!isset($_GET['id'])) {
            $error = "Нет лота";
            $page_content = include_template('404.php', ['error' => $error]);
        } else {
            $id = $_GET['id'];
            $sql = "SELECT lots.title, lots.id, lots.description, lots.image, author,  categories.name, bet_step, end_date, start_price
                FROM lots INNER JOIN categories ON lots.category = categories.id
                WHERE lots.id = ?";

            $stmt = db_get_prepare_stmt($con, $sql, [$id]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (!$result || mysqli_num_rows($result) == 0) {
                $error = "Лот с таким id еще не существует :(";
                $page_content = include_template('404.php', ['error' => $error]);
            } else {
                $lot = mysqli_fetch_assoc($result);
                $user_data = $_SESSION['user'];
                $is_author = 0;

                if ($lot['author'] == $user_data['id']) {
                    $is_author = 1;
                }


                $sql = "SELECT bet_value, bet_step, lots.end_date
                            FROM bets INNER JOIN lots ON bets.lot_id = lots.id
                            WHERE lots.id = ?
                            ORDER BY bets.created_date DESC
                            LIMIT 1";
                $stmt = db_get_prepare_stmt($con, $sql, [$id]);
                mysqli_stmt_execute($stmt);
                $result_bets = mysqli_stmt_get_result($stmt);
                $price_data = [];

                if (!$result_bets || mysqli_num_rows($result_bets) == 0) {
                    $price_data['current_price'] = $lot['start_price'];
                } else {
                    $bet_data = mysqli_fetch_assoc($result_bets);
                    $price_data['current_price'] = $bet_data['bet_value'];
                }

                $price_data['next_bet'] = $price_data['current_price'] + $lot['bet_step'];
                $price_data['end_date'] = $lot['end_date'];

                $sql ="SELECT bet_value, DATE_FORMAT(bets.created_date, '%d.%m.%y в %H:%i') as my_date, bets.created_date, users.name, users.id
                        FROM bets INNER JOIN users ON bets.user_id = users.id
                        INNER JOIN lots ON bets.lot_id = lots.id
                        WHERE lots.id = ?
                        ORDER BY bets.created_date DESC";
                $stmt = db_get_prepare_stmt($con, $sql, [$id]);
                mysqli_stmt_execute($stmt);
                $result_history_bets = mysqli_stmt_get_result($stmt);

                $has_bet = 0;

                if ($result_history_bets && mysqli_num_rows($result_history_bets) > 0) {
                    $history_bets = mysqli_fetch_all($result_history_bets, MYSQLI_ASSOC);
                    foreach ($history_bets as $key => $val){
                        if ($val['id'] == $_SESSION['user']['id']){
                            $has_bet++;
                        }
                    }
                }

                if ($_SERVER ['REQUEST_METHOD'] == 'POST') { // we got POST request, start to process data
                    $cost = $_POST['cost'];
                    $required = ['cost'];
                    $numeric = ['cost'];
                    $dict = ['cost' => 'Ваша ставка'];
                    $errors;
                    if (empty($cost)) {
                        $errors = 'Пожалуйста, заполните это поле';
                    } else if (!is_numeric($cost)) {
                        $errors = 'Пожалуйста, введите число';
                    } else if ($cost < $price_data['next_bet']) {
                        $errors = 'Ставка должна быть не меньше минимальной';
                    }

                    if ($errors) { // if there ARE errors
                        $page_content = include_template('tmpl_lot.php', ['categories' => $categories, 'lot' => $lot, 'user_data' => $user_data, 'price_data' => $price_data, 'cost' => $cost, 'errors' => $errors, 'history_bets' => $history_bets]);

                    } else { // there are NO errors
                        $sql = 'INSERT INTO bets (created_date, bet_value, user_id, lot_id) 
                    VALUES (NOW(), ?, ?, ?)';
                        $stmt = db_get_prepare_stmt($con, $sql, [$cost, $user_data['id'], $id]);
                        $result = mysqli_stmt_execute($stmt);

                        if ($result) { // successfully inserted the bet
                            $bet_id = mysqli_insert_id($con);

                            header("Location: lot.php?id=" . $id);
                        } else { // problem while insert
                            $content = include_template('404.php', ['error' => mysqli_error($con)]);
                        }
                    }
                }
                else {
                    $page_content = include_template('tmpl_lot.php', ['categories' => $categories, 'lot' => $lot, 'user_data' => $user_data, 'price_data' => $price_data, 'history_bets' => $history_bets, 'has_bet' => $has_bet, 'is_author' => $is_author]);
                }

            }
        }
    }
}

            $layout_content = include_template('layout.php',
                ['content' => $page_content,
                    'categories' => $categories,
                    'title' => $lot['title'],
                    'user_data' => $user_data]);

            print($layout_content);
            ?>