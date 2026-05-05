<?php

session_start();
include 'ConfReg.php';

$actApps = FormReg::GetActiveApp();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['userForm'])) {
        FormReg ::DeleteApp($_POST['userForm']);
    
        $_SESSION['admin_message'] = 'Записи удалены';
    }
    header('Location: admin.php');
    exit();
}

$message = isset($_SESSION['admin_message']) ? $_SESSION['admin_message'] : '';
unset($_SESSION['admin_message']);
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Панель администратора</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            .delete-btn {
                margin-top: 20px;
                padding: 10px 20px;
            }
        </style>
    </head> 

    <body>
        <h1>Панель админа</h1>
        <p>Записи:</p>
        <form action='' method='POST'>
            <table>
                <thead>
                    <tr>
                        <th>Выбор</th>
                        <th>Дата и время</th>
                        <th>IP</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Тема</th>
                        <th>Способ оплаты</th>
                        <th>Согласие на рассылку</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actApps as $index => $app): ?>
                        <tr>
                            <td>
                                <input type='checkbox' name='userForm[]' value="<?= htmlspecialchars($index) ?>">
                            </td>
                            <?php foreach ($app as $field): ?>
                                <td><?= htmlspecialchars($field) ?></td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <button type='submit' class='delete-btn'>Удалить</button>
        </form>
    </body>
</html>