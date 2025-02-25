<?php
session_start();
include 'db.php';

// Получаем список товаров
$query = $pdo->query("SELECT * FROM products");
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог - Copy Star</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Каталог товаров</h1>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="cart.php">Корзина</a></li>
            <li><a href="about.php">О нас</a></li>
            <?php if (!isset($_SESSION['user'])): ?>
                <li><a href="register.php">Регистрация</a></li>
                <li><a href="login.php">Авторизация</a></li>
            <?php else: ?>
                <li><a href="logout.php">Выход</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <h2>Наши товары</h2>
    <div class="catalog">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p>Цена: <?= number_format($product['price'], 2) ?> руб.</p>
                <a href="product.php?id=<?= $product['id'] ?>" class="btn">Подробнее</a>
                <a href="cart.php?action=add&id=<?= $product['id'] ?>" class="btn">Добавить в корзину</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<footer>
    <p>&copy; 2025 Copy Star. Все права защищены.</p>
</footer>
</body>
</html>
