<?php
session_start();
include 'db.php';

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
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Copy Star</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1><?= htmlspecialchars($product['name']) ?></h1>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="catalog.php">Каталог</a></li>
            <li><a href="cart.php">Корзина</a></li>
        </ul>
    </nav>
</header>
<main>
    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
    <p>Цена: <?= number_format($product['price'], 2) ?> руб.</p>
    <a href="cart.php?action=add&id=<?= $product['id'] ?>" class="btn">Добавить в корзину</a>
</main>
</body>
</html>
