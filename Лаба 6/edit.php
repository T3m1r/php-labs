<?php
$pdo = new PDO("mysql:host=localhost;dbname=kindergarten_db;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = (int)$_GET["id"];
$query = $pdo->prepare("SELECT * FROM children WHERE id = ?");
$query->execute(array($id));
$child = $query->fetch(PDO::FETCH_ASSOC);

if (!$child) {
    exit("Ребёнок не найден.");
}

if (isset($_POST["save"])) {
    $query = $pdo->prepare("UPDATE children SET full_name=?, birth_date=?, group_name=?, parent_phone=?, enrollment_date=? WHERE id=?");
    $query->execute(array(
        $_POST["full_name"],
        $_POST["birth_date"],
        $_POST["group_name"],
        $_POST["parent_phone"],
        $_POST["enrollment_date"],
        $id
    ));
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Изменить запись</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Изменить данные ребёнка</h1>
<form method="post">
    <p>ФИО: <input name="full_name" value="<?= htmlspecialchars($child["full_name"]) ?>" required></p>
    <p>Дата рождения: <input type="date" name="birth_date" value="<?= $child["birth_date"] ?>" required></p>
    <p>Группа: <input name="group_name" value="<?= htmlspecialchars($child["group_name"]) ?>" required></p>
    <p>Телефон родителя: <input name="parent_phone" value="<?= htmlspecialchars($child["parent_phone"]) ?>" required></p>
    <p>Дата зачисления: <input type="date" name="enrollment_date" value="<?= $child["enrollment_date"] ?>" required></p>
    <button type="submit" name="save">Сохранить</button>
    <a href="index.php">Назад</a>
</form>
</body>
</html>