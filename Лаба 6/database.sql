CREATE DATABASE IF NOT EXISTS kindergarten_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kindergarten_db;
CREATE TABLE IF NOT EXISTS children (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(120) NOT NULL,
 birth_date DATE NOT NULL,
 group_name VARCHAR(30) NOT NULL,
 parent_phone VARCHAR(30) NOT NULL,
 enrollment_date DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO children (full_name,birth_date,group_name,parent_phone,enrollment_date) VALUES
('Иванов Иван Иванович','2020-03-14','Солнышко','+7 900 111-22-33','2023-09-01'),
('Петрова Анна Сергеевна','2019-11-02','Звёздочки','+7 900 222-33-44','2022-09-01');