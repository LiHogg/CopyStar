<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Доступ запрещен.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image']; // В реальном проекте нужно загружать файл!

    if (empty($name) || empty($price) || empty($description) || empty($image)) {
        die("Заполните все поля.");
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $image, $description]);

    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить товар</title>
</head>
<body>
<h2>Добавить товар</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Название товара" required>
    <input type="number" name="price" placeholder="Цена" required>
    <input type="text" name="image" placeholder="Ссылка на изображение" required>
    <textarea name="description" placeholder="Описание товара" required></textarea>
    <button type="submit">Добавить</button>
</form>
</body>
</html>
