<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>О нас - Copy Star</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>О компании Copy Star</h1>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="catalog.php">Каталог</a></li>
            <li><a href="cart.php">Корзина</a></li>
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
    <h2>Кто мы?</h2>
    <p>Компания <strong>Copy Star</strong> — ведущий поставщик копировального оборудования в России.</p>
    <p>Мы предлагаем широкий ассортимент современных решений для бизнеса, включая принтеры, сканеры и копировальные машины.</p>

    <h2>Наша миссия</h2>
    <p>Наша цель — предоставить клиентам **высококачественное оборудование** по доступным ценам, обеспечивая высокий уровень сервиса и технической поддержки.</p>

    <h2>Почему выбирают нас?</h2>
    <ul>
        <li>Большой ассортимент оборудования</li>
        <li>Гарантия качества на все товары</li>
        <li>Профессиональная поддержка клиентов</li>
        <li>Доставка по всей России</li>
    </ul>

    <h2>Наши контакты</h2>
    <p><strong>Адрес:</strong> Москва, ул. Техническая, д. 10</p>
    <p><strong>Телефон:</strong> +7 (495) 123-45-67</p>
    <p><strong>Email:</strong> support@copystar.com</p>
</main>

<footer>
    <p>&copy; 2025 Copy Star. Все права защищены.</p>
</footer>
</body>
</html>
