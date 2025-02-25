<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id_query = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$user_id_query->execute([$user]);
$user_id = $user_id_query->fetchColumn();

if (!$user_id) {
    die("Ошибка получения ID пользователя.");
}

// Добавление товара в корзину
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1) 
                           ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    $stmt->execute([$user_id, $product_id]);
    header("Location: cart.php");
    exit();
}

// Обновление количества товаров
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        $quantity = (int)$quantity;
        if ($quantity > 0) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $user_id, $product_id]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        }
    }
    header("Location: cart.php");
    exit();
}

// Удаление товара из корзины
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $_GET['id']]);
    header("Location: cart.php");
    exit();
}

// Оформление заказа
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    $address = trim($_POST['address']);

    if (empty($address)) {
        echo "Ошибка: Укажите адрес доставки!";
    } else {
        // Рассчитываем общую сумму заказа
        $total_query = $pdo->prepare("SELECT SUM(products.price * cart.quantity) FROM cart 
                                      JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
        $total_query->execute([$user_id]);
        $total_price = $total_query->fetchColumn();

        // Записываем заказ в БД
        $order_stmt = $pdo->prepare("INSERT INTO orders (user_id, address, total_price) VALUES (?, ?, ?)");
        $order_stmt->execute([$user_id, $address, $total_price]);
        $order_id = $pdo->lastInsertId();

        // Записываем товары в order_items
        $cart_items = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
        $cart_items->execute([$user_id]);

        while ($item = $cart_items->fetch(PDO::FETCH_ASSOC)) {
            $order_item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                              VALUES (?, ?, ?, ?)");
            $order_item_stmt->execute([$order_id, $item['product_id'], $item['quantity'],
                                       $item['quantity'] * $item['price']]);
        }

        // Очищаем корзину
        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

        echo "<p>Заказ оформлен! Доставка по адресу: " . htmlspecialchars($address) . "</p>";
    }
}


// Получение товаров из корзины
$cart = $pdo->prepare("SELECT products.*, cart.quantity 
                       FROM cart 
                       JOIN products ON cart.product_id = products.id 
                       WHERE cart.user_id = ?");
$cart->execute([$user_id]);
$items = $cart->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина - Copy Star</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Корзина</h1>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="catalog.php">Каталог</a></li>
            <li><a href="logout.php">Выход</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Ваши товары</h2>
    <?php if (count($items) > 0): ?>
        <form method="POST">
            <table>
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Удалить</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?> руб.</td>
                        <td>
                            <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1">
                        </td>
                        <td><a href="cart.php?action=delete&id=<?= $item['id'] ?>" class="btn">Удалить</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit" name="update_cart">Обновить корзину</button>
        </form>

        <h2>Адрес доставки</h2>
        <form method="POST">
            <input type="text" name="address" placeholder="Введите адрес доставки" required>
            <button type="submit" name="checkout">Заказать</button>
        </form>
    <?php else: ?>
        <p>Ваша корзина пуста.</p>
    <?php endif; ?>
    <br>
    <a href="catalog.php" class="btn">Продолжить покупки</a>
</main>

<footer>
    <p>&copy; 2025 Copy Star. Все права защищены.</p>
</footer>
</body>
</html>
