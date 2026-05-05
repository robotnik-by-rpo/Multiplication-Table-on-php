<?php

$csvFile = 'form/reg.csv';

// чтение
function readApplications($csvFile) {
    $app = [];
    if (file_exists($csvFile)) {
        $lines = file($csvFile, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $index => $line) {
            if (trim($line) === '') continue;
            $data = explode('|', $line);
            if (count($data) < 10) {
                $data[] = '0'; // 0 - не удалена, 1 - удалена
            }
            $app[$index] = $data;
        }
    }
    return $app;
}

// сохранение в csv
function saveApplications($csvFile, $apps) {
    $lines = [];
    foreach ($apps as $app) {
        $lines[] = implode('|', $app);
    }
    file_put_contents($csvFile, implode("\n", $lines));
}

$allApps = readApplications($csvFile);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['userForm'])) {
        foreach ($_POST['userForm'] as $index) {
            if (isset($allApps[$index])) {
                $allApps[$index][count($allApps[$index]) - 1] = '1';
            }
        }
        saveApplications($csvFile, $allApps);
    }
    header('Location: admin.php');
    exit();
}

$items = [];
foreach ($allApps as $index => $app) {
    $deleted = end($app) == '1';
    if (!$deleted) {

        $displayData = array_slice($app, 0, -1);
        $items['reg_' . $index] = implode('|', $displayData);
    }
}
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
                    <?php foreach ($items as $filename => $item): 
                        $data = explode('|', $item);
                    ?>
                        <tr>
                            <td>
                                <input type='checkbox' name='userForm[]' value="<?= htmlspecialchars(str_replace('reg_', '', $filename)) ?>">
                            </td>
                            <?php foreach ($data as $field): ?>
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