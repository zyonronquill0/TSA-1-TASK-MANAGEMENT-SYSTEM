-- Import into a NEW, EMPTY MySQL/MariaDB database using phpMyAdmin.
-- Select the database first. No CREATE DATABASE privilege is needed.
-- The dated sample export is in tasks_db.sql; this version uses
-- yesterday, today, and tomorrow in Philippine time on the import date.

SET NAMES utf8mb4;
SET @created_at = DATE_ADD(UTC_TIMESTAMP(), INTERVAL 8 HOUR);
SET @today = DATE(@created_at);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    task_date DATE NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tasks (title, status, task_date, created_at) VALUES
('Clean my room', 'completed', DATE_SUB(@today, INTERVAL 1 DAY), @created_at),
('Read class notes', 'completed', DATE_SUB(@today, INTERVAL 1 DAY), @created_at),
('Finish my web systems assignment', 'in_progress', @today, @created_at),
('Go for a jog', 'pending', @today, @created_at),
('Watch Avengers: Endgame', 'pending', @today, @created_at),
('Do my math homework', 'pending', @today, @created_at),
('Buy groceries', 'pending', DATE_ADD(@today, INTERVAL 1 DAY), @created_at),
('Watch Spider-Man: No Way Home', 'pending', DATE_ADD(@today, INTERVAL 1 DAY), @created_at);

INSERT INTO users (username, full_name, email, created_at) VALUES
('zyon_dev', 'Lord Zyon Ronquillo', 'zyon.ronquillo@example.com', @created_at);
