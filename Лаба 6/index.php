<?php
$pdo = new PDO("mysql:host=localhost;dbname=kindergarten_db;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST["add"])) {
    $sql = "INSERT INTO children (full_name, birth_date, group_name, parent_phone, enrollment_date)
            VALUES (?, ?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->execute(array(
        $_POST["full_name"],
        $_POST["birth_date"],
        $_POST["group_name"],
        $_POST["parent_phone"],
        $_POST["enrollment_date"]
    ));
    header("Location: index.php");
    exit;
}

$sort = "full_name";
if (isset($_GET["sort"])) {
    $allowed = array("full_name", "birth_date", "group_name", "enrollment_date");
    if (in_array($_GET["sort"], $allowed)) {
        $sort = $_GET["sort"];
    }
}
$children = $pdo->query("SELECT * FROM children ORDER BY " . $sort)->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Детский сад</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Учёт детей в детском саду</h1>

<h2>Добавить ребёнка</h2>
<form method="post">
    <p>ФИО: <input name="full_name" required></p>
    <p>Дата рождения: <input type="date" name="birth_date" required></p>
    <p>Группа: <input name="group_name" required></p>
    <p>Телефон родителя: <input name="parent_phone" required></p>
    <p>Дата зачисления: <input type="date" name="enrollment_date" required></p>
    <button type="submit" name="add">Добавить</button>
</form>

<h2>Список детей</h2>
<table>
    <tr>
        <th>ID</th>
        <th><a href="?sort=full_name">ФИО</a></th>
        <th><a href="?sort=birth_date">Дата рождения</a></th>
        <th><a href="?sort=group_name">Группа</a></th>
        <th>Телефон</th>
        <th><a href="?sort=enrollment_date">Дата зачисления</a></th>
        <th>Изменить</th>
    </tr>
    <?php foreach ($children as $child): ?>
        <tr>
            <td><?= $child["id"] ?></td>
            <td><?= htmlspecialchars($child["full_name"]) ?></td>
            <td><?= $child["birth_date"] ?></td>
            <td><?= htmlspecialchars($child["group_name"]) ?></td>
            <td><?= htmlspecialchars($child["parent_phone"]) ?></td>
            <td><?= $child["enrollment_date"] ?></td>
            <td><a href="edit.php?id=<?= $child["id"] ?>">Изменить</a></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>