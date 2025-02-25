<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Доступ запрещен.");
}

if (!isset($_GET['id'])) {
    die("Товар не найден.");
}

$id = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$query->execute([$id]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Товар не найден.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    if (empty($name) || empty($price) || empty($description) || empty($image)) {
        die("Заполните все поля.");
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $price, $image, $description, $id]);

    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать товар</title>
</head>
<body>
<h2>Редактировать товар</h2>
<form method="POST">
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    <input type="number" name="price" value="<?= $product['price'] ?>" required>
    <input type="text" name="image" value="<?= htmlspecialchars($product['image']) ?>" required>
    <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
    <button type="submit">Сохранить</button>
</form>
</body>
</html>
