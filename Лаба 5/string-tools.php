<?php
// Примеры простых строковых функций PHP.
$text = "Торонто";
echo "Название: " . $text . "<br>";
echo "Длина: " . mb_strlen($text) . "<br>";
echo "Первая буква: " . mb_substr($text, 0, 1) . "<br>";
echo "Позиция «то»: " . mb_stripos($text, "то");