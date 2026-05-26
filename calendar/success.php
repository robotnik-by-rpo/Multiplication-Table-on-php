<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Успех - Мой календарь</title>
    <style>
        body { font-family: Andale Mono, monospace; margin: 20px; text-align: center; }
        .success-box { margin-top: 50px; padding: 30px; border: 1px solid black; border-radius: 5px; }
        h2 { color: green; }
        button { padding: 10px 20px; margin-top: 20px; cursor: pointer; margin: 10px; }
    </style>
</head>
<body>
    <div class="success-box">
        <h2>Задача успешно добавлена!</h2>
        <p>Ваша новая задача сохранена в календаре.</p>
        <a href="index.php"><button>Вернуться к списку задач</button></a>
        <a href="create_task.php"><button>Создать ещё одну задачу</button></a>
    </div>
</body>
</html>