<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!empty($_POST['userForm'])) {
        foreach ($_POST['userForm'] as $filename){
            $file = 'form/' . basename($filename) . '.txt';
            if (file_exists($file)){
                unlink($file);
            }
        }
    }
    header('Location: admin.php');
    exit();
}
$items = [];
$files = glob('form/reg_*.txt');
foreach ($files as $file) {
    $filename = basename($file, '.txt');
    $items[$filename] = file_get_contents($file);
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Панель администратора</title>
    </head> 

    <body>
        <h1>Панель админа</h1>
        <p>Записи:</p>
        <form action='' method='POST'>
            <ul>
                <?php foreach ($items as $filename => $item): ?>
                    <li>
                
                        <input type='checkbox' name='userForm[]' value="<?= htmlspecialchars($filename) ?>">
                        <b><?= htmlspecialchars($filename)?></b>
                        <br> <?= nl2br(htmlspecialchars($item)) ?></br>
            
                    </li>
                <?php endforeach ?>
            </ul>
            <button type='submit'>Удалить</button>
        </form>
    </body>
</html>