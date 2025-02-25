<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Валидация ФИО (только кириллица)
    if (!preg_match('/^[А-Яа-яЁё\s]+$/u', $name)) {
        die("Ошибка: ФИО должно содержать только буквы кириллицы.");
    }

    // Валидация логина (латиница, цифры, тире)
    if (!preg_match('/^[a-zA-Z0-9-]+$/', $username)) {
        die("Ошибка: Логин может содержать только латинские буквы, цифры и тире.");
    }

    // Валидация пароля (не менее 6 символов)
    if (strlen($password) < 6) {
        die("Ошибка: Пароль должен быть не менее 6 символов.");
    }

    // Хеширование пароля
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Проверка уникальности логина и email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        die("Ошибка: Такой логин или email уже существует.");
    }

    // Запись в базу данных
    $stmt = $pdo->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $username, $email, $passwordHash])) {
        header("Location: login.php");
        exit();
    } else {
        echo "Ошибка регистрации.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Регистрация</h2>
<form method="POST">
    <input type="text" name="name" placeholder="ФИО (кириллица)" required>
    <input type="text" name="username" placeholder="Логин (латиница, цифры, тире)" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Пароль (мин. 6 символов)" required>
    <button type="submit">Зарегистрироваться</button>
</form>
<p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</body>
</html>
