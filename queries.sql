/* Заполнить существующий список категорий */
INSERT INTO categories (name) VALUES ('Доски и лыжи');
INSERT INTO categories (name) VALUES ('Крепления');
INSERT INTO categories (name) VALUES ('Ботинки');
INSERT INTO categories (name) VALUES ('Одежда');
INSERT INTO categories (name) VALUES ('Инструменты');
INSERT INTO categories (name) VALUES ('Разное');

/*  Добавить пользователей */
INSERT INTO users (reg_date, email, name, password, contacts) VALUES ('2018-11-24 19:19:19', 'user1@email.com', 'Name1', 'password1', 'Звоните мне по номеру 0123456789');
INSERT INTO users (reg_date, email, name, password, contacts) VALUES ('2017-11-24 19:19:19', 'user2@email.com', 'Name2', 'password1', 'Не звоните мне');
INSERT INTO users (reg_date, email, name, password, contacts) VALUES ('2016-11-24 19:19:19', 'user3@email.com', 'Name3', 'password1', '222 33 44');

/* Заполнить существующий список объявлений */
INSERT INTO lots (created_date, title, description, image, start_price, end_date, bet_step, author, winner, category) 
VALUES ('2018-11-24 19:19:19', '2014 Rossignol District Snowboard', 'Rossignol', 'img/lot-1.jpg', '10999', '2019-01-01 00:00:00', '1', '1', '3', '1');
INSERT INTO lots (created_date, title, description, image, start_price, end_date, bet_step, author, winner, category) 
VALUES ('2018-11-23 19:19:19', 'DC Ply Mens 2016/2017 Snowboard', 'Snowboard', 'img/lot-2.jpg', '159999', '2019-01-01 00:00:00',  '10', '1','2', '1' );
INSERT INTO lots (created_date, title, description, image, start_price, end_date, bet_step, author, winner, category) 
VALUES ('2018-11-22 19:19:19', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления', 'img/lot-3.jpg', '8000', '2019-01-01 00:00:00', '100', '2','1','2');
INSERT INTO lots (created_date, title, description, image, start_price, end_date, bet_step, author, winner, category) 
VALUES ('2018-11-21 19:19:19', 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки супер', 'img/lot-4.jpg', '10999', '2019-01-01 00:00:00', '1000', '2', '1', '3');
INSERT INTO lots (created_date, title, image, start_price, end_date, bet_step, author, category) 
VALUES ('2018-11-24 19:19:19', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', '7500','2019-01-01 00:00:00', '10000', '1', '4');
INSERT INTO lots (created_date, title, image, start_price, end_date, bet_step, author, category) 
VALUES ('2018-11-19 19:19:19', 'Маска Oakley Canopy', 'img/lot-6.jpg', '5400', '2019-01-01 00:00:00', '100000', '2', '6');

/* Добавить ставки */
INSERT INTO bets (created_date, bet_value, user_id, lot_id) VALUES ('2018-11-24 20:19:19', '11000', '3', '1');
INSERT INTO bets (created_date, bet_value, user_id, lot_id) VALUES ('2018-11-24 19:20:19', '170000', '2', '5');
INSERT INTO bets (created_date, bet_value, user_id, lot_id) VALUES ('2018-11-15 19:19:20', '8000', '1', '6');
INSERT INTO bets (created_date, bet_value, user_id, lot_id) VALUES ('2018-11-16 19:19:20', '80000', '1', '6');
INSERT INTO bets (created_date, bet_value, user_id, lot_id) VALUES ('2018-11-17 19:19:20', '85000', '1', '6');
INSERT INTO bets (created_date, bet_value, user_id, lot_id) VALUES ('2018-11-18 19:19:20', '800000', '1', '6');

/* получить все категории; */
SELECT * FROM categories;

/* получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории; */
SELECT lots.title, lots.start_price, MAX(bet_value), lots.image,  categories.name 
FROM lots INNER JOIN bets ON lots.id = bets.lot_id 
INNER JOIN categories ON lots.category = categories.id 
WHERE lots.created_date >= '2018-11-01 00:00:00' AND lots.winner IS NULL
GROUP BY lots.id;

/* показать лот по его id. Получите также название категории, к которой принадлежит лот */
SELECT lots.id, title, categories.name 
FROM lots INNER JOIN categories ON lots.category = categories.id
WHERE lots.id = '1';

/* обновить название лота по его идентификатору; */
UPDATE lots SET title = 'Маска+++ Oakley Canopy'
WHERE id = '6';

/* получить список самых свежих ставок для лота по его идентификатору; */
SELECT bet_value, bets.created_date, users.name, lots.title
FROM bets INNER JOIN users ON bets.user_id = users.id
INNER JOIN lots ON bets.lot_id = lots.id
WHERE lots.id = '6'
ORDER BY bets.created_date DESC;