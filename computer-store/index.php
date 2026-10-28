
<?php
// Проверка работы PHP
$pageTitle = "Мой первый PHP-сайт";
$studentName = "Темир";

echo "<!DOCTYPE html>";
echo "<html lang='ru'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>$pageTitle</title>";
echo "</head>";
echo "<body>";
echo "<h1>PHP работает!</h1>";
echo "<p>Студент: $studentName</p>";
echo "<p>Результат вычисления: " . (10 + 20) . "</p>";
echo "</body>";
echo "</html>";
?>
