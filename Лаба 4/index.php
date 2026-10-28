<?php
$products = array(
    "Процессор" => 14500,
    "Видеокарта" => 38900,
    "Оперативная память" => 4200,
    "SSD 1 ТБ" => 6500,
    "Монитор" => 11900
);

$name = "";
$email = "";
$total = 0;
$discount = 0;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Введите имя и правильный email.";
    } else {
        foreach ($products as $product => $price) {
            $count = (int)$_POST[str_replace(" ", "_", $product)];
            if ($count < 0) {
                $count = 0;
            }
            $total = $total + $price * $count;
        }

        if ($total >= 50000) {
            $discount = $total * 0.10;
        } elseif ($total >= 25000) {
            $discount = $total * 0.05;
        }
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Компьютерный магазин</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Компьютерный магазин</h1>
<form method="post">
    <p>Имя: <input name="name" value="<?= htmlspecialchars($name) ?>" required></p>
    <p>Email: <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required></p>

    <h2>Товары</h2>
    <?php foreach ($products as $product => $price): ?>
        <p>
            <?= $product ?> — <?= $price ?> руб.
            Количество:
            <input type="number" name="<?= str_replace(" ", "_", $product) ?>" min="0" max="20"
                   value="<?= isset($_POST[str_replace(" ", "_", $product)]) ? (int)$_POST[str_replace(" ", "_", $product)] : 0 ?>">
        </p>
    <?php endforeach; ?>
    <button type="submit">Посчитать заказ</button>
</form>

<?php if ($error != ""): ?>
    <p class="error"><?= $error ?></p>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <h2>Результат заказа</h2>
    <p>Покупатель: <?= htmlspecialchars($name) ?></p>
    <p>Стоимость: <?= $total ?> руб.</p>
    <p>Скидка: <?= $discount ?> руб.</p>
    <p><strong>К оплате: <?= $total - $discount ?> руб.</strong></p>
<?php endif; ?>
</body>
</html>