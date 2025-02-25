<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Получаем ID пользователя
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$user]);
$user_id = $stmt->fetchColumn();

// Получаем заказы пользователя
$orders_query = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orders_query->execute([$user_id]);
$orders = $orders_query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль - Copy Star</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Профиль</h1>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="cart.php">Корзина</a></li>
            <li><a href="logout.php">Выход</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>История заказов</h2>
    <?php if (count($orders) > 0): ?>
        <table>
            <tr>
                <th>ID заказа</th>
                <th>Адрес доставки</th>
                <th>Сумма</th>
                <th>Дата</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['address']) ?></td>
                    <td><?= number_format($order['total_price'], 2) ?> руб.</td>
                    <td><?= $order['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Вы еще не делали заказов.</p>
    <?php endif; ?>
</main>
</body>
</html>
