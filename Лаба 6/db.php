<?php
declare(strict_types=1);
const DB_HOST='127.0.0.1';const DB_NAME='kindergarten_db';const DB_USER='root';const DB_PASS='';
try {
 $pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[
  PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES=>false,
 ]);
} catch(PDOException $exception) {
 http_response_code(500);
 exit('Не удалось подключиться к базе данных. Запустите MySQL и импортируйте database.sql через phpMyAdmin.');
}