<?php
session_start();
require_once 'db.php';

$database = new DataBase();
$types = $database->GetTypes();
?>


<head>
    <meta charset="UTF-8">
    <title>Создание задачи - Мой календарь</title>
    <style>
        body { font-family: Andale Mono, monospace; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; font-weight: bold; }
        .required { color: red; }
        input, select, textarea { padding: 5px; width: 300px; }
        textarea { width: 300px; height: 100px; }
        button { padding: 5px 15px; cursor: pointer; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Создание новой задачи</h1>
    
    <form method="POST" action="process.php">
        <input type="hidden" name="action" value="create">
        
        <div class="form-group">
            <label>Тема <span class="required">*</span>:</label>
            <input type="text" name="theme" required>
        </div>
        
        <div class="form-group">
            <label>Тип <span class="required">*</span>:</label>
            <select name="type_id" required>
                <option value="">Выберите тип</option>
                <?php foreach($types as $type): ?>
                    <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Место:</label>
            <input type="text" name="place">
        </div>
        
        <div class="form-group">
            <label>Дата:</label>
            <input type="text" name="date" placeholder="ДД.ММ.ГГГГ или ГГГГ-ММ-ДД">
        </div>
        
        <div class="form-group">
            <label>Время:</label>
            <select name="time">
                <option value="">Не указано</option>
                <option value="00:00">00:00</option>
                <option value="01:00">01:00</option>
                <option value="02:00">02:00</option>
                <option value="03:00">03:00</option>
                <option value="04:00">04:00</option>
                <option value="05:00">05:00</option>
                <option value="06:00">06:00</option>
                <option value="07:00">07:00</option>
                <option value="08:00">08:00</option>
                <option value="09:00">09:00</option>
                <option value="10:00">10:00</option>
                <option value="11:00">11:00</option>
                <option value="12:00">12:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
                <option value="17:00">17:00</option>
                <option value="18:00">18:00</option>
                <option value="19:00">19:00</option>
                <option value="20:00">20:00</option>
                <option value="21:00">21:00</option>
                <option value="22:00">22:00</option>
                <option value="23:00">23:00</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Длительность:</label>
            <select name="duration">
                <option value="">Не указано</option>
                <option value="00:15:00">15 минут</option>
                <option value="00:30:00">30 минут</option>
                <option value="01:00:00">1 час</option>
                <option value="01:30:00">1.5 часа</option>
                <option value="02:00:00">2 часа</option>
                <option value="03:00:00">3 часа</option>
                <option value="04:00:00">4 часа</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Комментарий <span class="required">*</span>:</label>
            <textarea name="comment" required></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit">Добавить задачу</button>
            <a href="index.php"><button type="button">Отмена</button></a>
        </div>
    </form>
</body>
</html>