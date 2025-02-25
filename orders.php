<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Доступ запрещен.");
}

// Получаем все заказы
$orders_query = $pdo->query("SELECT orders.*, users.username FROM orders 
                             JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC");
$orders = $orders_query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказы - Админ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Все заказы</h1>
    <nav>
        <ul>
            <li><a href="admin.php">Назад в админ-панель</a></li>
            <li><a href="logout.php">Выход</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Список заказов</h2>
    <?php if (count($orders) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Адрес</th>
                <th>Сумма</th>
                <th>Дата</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td><?= htmlspecialchars($order['address']) ?></td>
                    <td><?= number_format($order['total_price'], 2) ?> руб.</td>
                    <td><?= $order['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Заказов пока нет.</p>
    <?php endif; ?>
</main>
</body>
</html>
