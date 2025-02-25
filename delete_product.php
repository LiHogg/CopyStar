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

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: admin.php");
exit();
?>
