<?php
session_start();

include 'ConfReg.php';

$sourse = isset($_GET['sourse']) ? $_GET['sourse'] : null;

if ($sourse === 'waiting') {
    sleep(3);
    header('Location: user.php?sourse=true');
    exit();
}

$formReg = new FormReg;

if ($_POST) {
    $registration = new FormReg($_POST);
    if ($registration->TakeData()){
        if($registration->Save()) {
            $_SESSION['registration_success'] = true;
            header('Location: user.php?sourse=waiting');
            exit();        
        }
    } else {
        $_SESSION['errors'] = $registration->GetErrors();  
        $_SESSION['post_data'] = $registration->GetData();
        header('Location: user.php');
        exit();
    }
}


if (isset($_SESSION['errors'])){
    $errors = $_SESSION['errors'];
    $postData = $_SESSION['post_data'];
    unset($_SESSION['errors']);
    unset($_SESSION['post_data']);
} else {
    $errors = [];
    $postData = [];
}

if ($sourse === 'true') {
    echo '<p>Регистрация успешна!</p>';
    echo '<p><a href="user.php">Вернуться к форме</a></p>';
    $flag = true;
    unset($_SESSION['registration_success']);
} else {
    $flag = false;
}
?>

<?php if (!$flag) { ?>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Регистрация</title>
        </head>
        <body>
            <?php if (!empty($errors)): ?>
                <p>Проверьте данные формы</p>
            <?php endif ?>
            <form action='' method='POST'>
                <div>
                    <br>Имя: <input type='text' name='name' value='<?= htmlspecialchars($postData['name'] ?? '') ?>'>
                    <?php echo $errors['name'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Фамилия: <input type='text' name='lastname' value='<?= htmlspecialchars($postData['lastname'] ?? '') ?>'>
                    <?php echo $errors['lastname'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Телефон: <input type='text' name='phone' value='<?= htmlspecialchars($postData['phone'] ?? '') ?>'>
                    <?php echo $errors['phone'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Электронная почта: <input type='text' name='email' value='<?= htmlspecialchars($postData['email'] ?? '') ?>'>
                    <?php echo $errors['email'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Тематика конфиренции:
                    <select name='themes'>
                        <option value=''>Тема</option>
                        <?php foreach ($formReg->GetThemes() as $key => $value): ?>
                            <option value='<?= $key ?>' <?= (($postData['themes'] ?? '') == $key) ? 'selected' : ''?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <?php echo $errors['themes'] ?? ''; ?>
                    </br>
                </div>

                <div>
                    <br>Выберите метод оплаты:
                    <select name="wallet">
                        <option value="">Выберите способ оплаты</option>
                        <?php foreach ($formReg->GetWallet() as $key => $value): ?>
                            <option value="<?= $key ?>" <?= (($postData['wallet'] ?? '') == $key) ? 'selected' : '' ?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <?php echo $errors['wallet'] ?? ''; ?>
                    </br>
                </div>

                <div>
                    <br>Согласны на рассылку
                    <input type='checkbox' name='agreement' value="yes"> 
                    </br>
                </div>
                <div>
                    <button type="submit">Отправить</button>
                </div>
            </form>

        </body>
    </html>
<?php } ?>