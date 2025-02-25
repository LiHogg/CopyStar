<?php
session_start();
session_destroy(); // Удаляем все данные сессии
header("Location: index.php"); // Перенаправление на главную страницу
exit();
?>
