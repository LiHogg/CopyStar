<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Copy Star - Главная</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Copy Star</h1>
    <nav>
        <ul>
            <li><a href="catalog.php">Каталог</a></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="cart.php">Корзина</a></li>
            <?php if (!isset($_SESSION['user'])): ?>
                <li><a href="register.php">Регистрация</a></li>
                <li><a href="login.php">Авторизация</a></li>
            <?php else: ?>
                <li><a href="profile.php">Профиль</a></li>
                <li><a href="logout.php">Выход</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <h2>Добро пожаловать в Copy Star!</h2>
    <p>Мы предлагаем лучшие копировальные аппараты и офисное оборудование.</p>
    <a href="catalog.php" class="btn">Перейти в каталог</a>
</main>

<footer>
    <p>&copy; 2025 Copy Star. Все права защищены.</p>
</footer>
</body>
</html>
