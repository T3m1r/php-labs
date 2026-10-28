<?php
$cities = array(
    "Торонто" => "Онтарио",
    "Монреаль" => "Квебек",
    "Ванкувер" => "Британская Колумбия",
    "Оттава" => "Онтарио",
    "Квебек" => "Квебек",
    "Калгари" => "Альберта",
    "Эдмонтон" => "Альберта",
    "Виннипег" => "Манитоба",
    "Галифакс" => "Новая Шотландия",
    "Виктория" => "Британская Колумбия"
);

$letter = "";
if (isset($_GET["letter"])) {
    $letter = mb_strtolower($_GET["letter"]);
}

$visits = 0;
if (file_exists("visits.txt")) {
    $visits = (int)file_get_contents("visits.txt");
}
$visits++;
file_put_contents("visits.txt", $visits);

$selectedCity = "";
if (isset($_GET["city"])) {
    $selectedCity = $_GET["city"];
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Справочник городов Канады</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Справочник городов Канады</h1>

<form method="get">
    <label>Первая буква города:
        <input name="letter" maxlength="1" value="<?= htmlspecialchars($letter) ?>">
    </label>
    <button type="submit">Найти</button>
</form>

<h2>Города</h2>
<ul>
<?php foreach ($cities as $city => $province): ?>
    <?php if ($letter == "" || mb_strtolower(mb_substr($city, 0, 1)) == $letter): ?>
        <li><a href="?city=<?= urlencode($city) ?>"><?= $city ?></a> — <?= $province ?></li>
    <?php endif; ?>
<?php endforeach; ?>
</ul>

<?php if ($selectedCity != "" && isset($cities[$selectedCity])): ?>
    <h2><?= $selectedCity ?></h2>
    <p>Провинция: <?= $cities[$selectedCity] ?></p>
    <p>Количество символов в названии: <?= mb_strlen($selectedCity) ?></p>
    <p>Первая буква: <?= mb_substr($selectedCity, 0, 1) ?></p>
    <p>Позиция строки «то»: <?php
        $position = mb_stripos($selectedCity, "то");
        echo $position === false ? "не найдена" : $position;
    ?></p>
<?php endif; ?>

<p>Посещений страницы: <?= $visits ?></p>
</body>
</html>