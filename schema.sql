CREATE DATABASE yeticave 
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
	
USE yeticave;

CREATE TABLE categories (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	name CHAR (32) UNIQUE NOT NULL
);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	reg_date DATETIME NOT NULL,
	email CHAR (128) UNIQUE NOT NULL,
	name CHAR (32) NOT NULL,	
	password CHAR (64) NOT NULL ,
	avatar CHAR (255), 
	contacts TEXT NOT NULL
);

CREATE TABLE lots (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	created_date DATETIME NOT NULL,
	title CHAR(128) NOT NULL,
	description CHAR(255) NOT NULL,
	image CHAR(255) NOT NULL,
	start_price INT NOT NULL,
	end_date DATETIME NOT NULL,
	bet_step INT NOT NULL,
	author INT NOT NULL,
	winner INT,
	category INT NOT NULL,
	FOREIGN KEY (author) REFERENCES users (id),
	FOREIGN KEY (winner) REFERENCES users (id),
	FOREIGN KEY (category) REFERENCES categories (id)
);

CREATE TABLE bets (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	created_date DATETIME NOT NULL,
	bet_value INT NOT NULL,
	user_id INT NOT NULL,
	lot_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users (id),
	FOREIGN KEY (lot_id) REFERENCES lots (id)
	
);

CREATE INDEX lot_name_index ON lots (title);
CREATE INDEX lot_description_index ON lots (description);