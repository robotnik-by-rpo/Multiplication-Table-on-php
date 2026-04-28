<?php
    $sourse = isset($_GET['source']) ? $_GET['source'] : null;
  
    if($sourse === 'waiting'){
        sleep(3);
        header('Location: global.php?source=true');
        exit();
    }

    $themes = [
        1 => 'Бизнес',
        2 => 'Технологии',
        3 => 'Реклама и Маркетинг',
    ];

    $wallet = [
        1 => 'WebMoney',
        2 => 'Яндекс.Деньги',
        3 => 'PayPal',
        4 => 'кредитная карта',    
    ];

    $nessessory = 'Поле необходимо для заполнения! <br>';
    $error = 'Ошибка в заполнения поля <br>';
    $flag = false;
    $errors = [];
    $regexpPhone = '/^8\d{10}$/';
    $regexpEmail = '/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,}$/';


    if ($_POST){
        if (empty($_POST['name'])){
            $errors['name'] = $nessessory;
        }
        if (empty($_POST['lastname'])) {
            $errors['lastname'] = $nessessory;
        }
        if (empty($_POST['email'])) {
            $errors['email'] = $nessessory;
        }
        elseif (!preg_match($regexpEmail, $_POST['email'])){
            $errors['email'] = $error;
        }

        if (empty($_POST['phone'])){
            $errors['phone'] = $nessessory;
        }
        elseif (!preg_match($regexpPhone, $_POST['phone'])){
            $errors['phone'] = $error;
        }
        if (empty($_POST['themes'])){
            $errors['themes'] = $nessessory;
        }
        if (empty($_POST['wallet'])) {
            $errors['wallet'] = $nessessory; 
        }

        if (empty($_POST['agreement'])) {
            $_POST['agreement'] = 'no';
        }

        if (!$errors) {
            $date = new DateTime();
            $dataPOST = print_r($_POST, true);
            $file = "./form/reg_{$date->format('Y-m-d H:i:s')}.txt";
            file_put_contents($file, $dataPOST);

            header('Location: global.php?source=waiting');
            exit();
        }

    }

    if($sourse === 'true'){
        echo '<p>Регистрация успешна!</p>';
        echo '<p><a href="user.php">Вернуться к форме</a></p>';
        $flag = true;
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
                    <br>Имя: <input type='text' name='name' value='<?= htmlspecialchars($_POST['name'] ?? '') ?>'>
                    <?php echo $errors['name'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Фамилия: <input type='text' name='lastname' value='<?= htmlspecialchars($_POST['lastname'] ?? '') ?>'>
                    <?php echo $errors['lastname'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Телефон: <input type='text' name='phone' value='<?= htmlspecialchars($_POST['phone'] ?? '') ?>'>
                    <?php echo $errors['phone'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Электронная почта: <input type='text' name='email' value='<?= htmlspecialchars($_POST['email'] ?? '') ?>'>
                    <?php echo $errors['email'] ?? ''; ?>
                    </br>
                </div>
                <div>
                    <br>Тематика конфиренции:
                    <select name='themes'>
                        <option value=''>Тема</option>
                        <?php foreach ($themes as $key => $value): ?>
                            <option value='<?= $key ?>' <?= (($_POST['themes'] ?? '') == $key) ? 'selected' : ''?>>
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
                        <?php foreach ($wallet as $key => $value): ?>
                            <option value="<?= $key ?>" <?= (($_POST['wallet'] ?? '') == $key) ? 'selected' : '' ?>>
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