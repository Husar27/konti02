-- users.sql
-- Uruchom w phpMyAdmin lub konsoli MySQL, aby utworzyć tabelę użytkowników
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
