<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Доступ запрещен.");
}

// Получаем список товаров
$query = $pdo->query("SELECT * FROM products");
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель - Copy Star</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Админ-панель</h1>
    <nav>
        <ul>
            <li><a href="orders.php">Заказы</a></li>
            <li><a href="logout.php">Выход</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Управление товарами</h2>
    <a href="add_product.php" class="btn">Добавить товар</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= number_format($product['price'], 2) ?> руб.</td>
                <td>
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn">Редактировать</a>
                    <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn" onclick="return confirm('Удалить товар?');">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<footer>
    <p>&copy; 2025 Copy Star. Все права защищены.</p>
</footer>
</body>
</html>
