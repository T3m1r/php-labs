<?php
// Подключение к базе данных MySQL.
$pdo = new PDO("mysql:host=localhost;dbname=kindergarten_db;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);