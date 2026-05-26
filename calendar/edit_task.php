<?php
session_start();
require_once 'db.php';

$database = new DataBase();
$types = $database->GetTypes();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$task = $database->GetTask($id);
if (empty($task)) {
    header('Location: index.php');
    exit;
}

$date = '';
$time = '';
if ($task['datetime']) {
    $parts = explode(' ', $task['datetime']);
    $date = $parts[0] ?? '';
    $time = substr($parts[1] ?? '', 0, 5);
}
?>

<head>
    <meta charset="UTF-8">
    <title>Редактирование задачи - Мой календарь</title>
    <style>
        body { font-family: Andale Mono, monospace; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; font-weight: bold; }
        .required { color: red; }
        input, select, textarea { padding: 5px; width: 300px; }
        textarea { width: 300px; height: 100px; }
        button { padding: 5px 15px; cursor: pointer; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Редактирование задачи №<?php echo $task['id']; ?></h1>
    
    <?php if($task['status'] == 'completed'): ?>
        <div style="color: orange; padding: 10px; margin-bottom: 20px;">
             Эта задача выполнена. Вы можете только просмотреть или удалить её.
        </div>
    <?php endif; ?>
    
    <form method="POST" action="process.php">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
        
        <div class="form-group">
            <label>Тема <span class="required">*</span>:</label>
            <input type="text" name="theme" required value="<?php echo htmlspecialchars($task['theme']); ?>"
                   <?php echo $task['status'] == 'completed' ? 'readonly' : ''; ?>>
        </div>
        
        <div class="form-group">
            <label>Тип <span class="required">*</span>:</label>
            <select name="type_id" required <?php echo $task['status'] == 'completed' ? 'disabled' : ''; ?>>
                <?php foreach($types as $type): ?>
                    <option value="<?php echo $type['id']; ?>" 
                            <?php echo $task['type_id'] == $type['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($type['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if($task['status'] == 'completed'): ?>
                <input type="hidden" name="type_id" value="<?php echo $task['type_id']; ?>">
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Место:</label>
            <input type="text" name="place" value="<?php echo htmlspecialchars($task['place'] ?? ''); ?>"
                   <?php echo $task['status'] == 'completed' ? 'readonly' : ''; ?>>
        </div>
        
        <div class="form-group">
            <label>Дата:</label>
            <input type="text" name="date" placeholder="ДД.ММ.ГГГГ" value="<?php echo $date; ?>"
                   <?php echo $task['status'] == 'completed' ? 'readonly' : ''; ?>>
        </div>
        
        <div class="form-group">
            <label>Время:</label>
            <select name="time" <?php echo $task['status'] == 'completed' ? 'disabled' : ''; ?>>
                <option value="">Не указано</option>
                <?php for($h = 0; $h < 24; $h++): ?>
                    <?php $hour = str_pad($h, 2, '0', STR_PAD_LEFT); ?>
                    <option value="<?php echo $hour; ?>:00" <?php echo $time == $hour . ':00' ? 'selected' : ''; ?>>
                        <?php echo $hour; ?>:00
                    </option>
                <?php endfor; ?>
            </select>
            <?php if($task['status'] == 'completed' && $time): ?>
                <input type="hidden" name="time" value="<?php echo $time; ?>:00">
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Длительность:</label>
            <select name="duration" <?php echo $task['status'] == 'completed' ? 'disabled' : ''; ?>>
                <option value="">Не указано</option>
                <option value="00:15:00" <?php echo $task['duration'] == '00:15:00' ? 'selected' : ''; ?>>15 минут</option>
                <option value="00:30:00" <?php echo $task['duration'] == '00:30:00' ? 'selected' : ''; ?>>30 минут</option>
                <option value="01:00:00" <?php echo $task['duration'] == '01:00:00' ? 'selected' : ''; ?>>1 час</option>
                <option value="01:30:00" <?php echo $task['duration'] == '01:30:00' ? 'selected' : ''; ?>>1.5 часа</option>
                <option value="02:00:00" <?php echo $task['duration'] == '02:00:00' ? 'selected' : ''; ?>>2 часа</option>
                <option value="03:00:00" <?php echo $task['duration'] == '03:00:00' ? 'selected' : ''; ?>>3 часа</option>
                <option value="04:00:00" <?php echo $task['duration'] == '04:00:00' ? 'selected' : ''; ?>>4 часа</option>
            </select>
            <?php if($task['status'] == 'completed' && $task['duration']): ?>
                <input type="hidden" name="duration" value="<?php echo $task['duration']; ?>">
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Комментарий <span class="required">*</span>:</label>
            <textarea name="comment" required <?php echo $task['status'] == 'completed' ? 'readonly' : ''; ?>><?php echo htmlspecialchars($task['comment']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Статус:</label>
            <select name="status" <?php echo $task['status'] == 'completed' ? 'disabled' : ''; ?>>
                <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>В процессе</option>
                <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>Выполнена</option>
            </select>
            <?php if($task['status'] == 'completed'): ?>
                <input type="hidden" name="status" value="completed">
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <?php if($task['status'] != 'completed'): ?>
                <button type="submit">Сохранить изменения</button>
            <?php endif; ?>
            <a href="process.php?action=delete_single&id=<?php echo $task['id']; ?>"><button type="button">Удалить задачу</button></a>
            <a href="index.php"><button type="button">Вернуться к списку</button></a>
        </div>
    </form>
</body>
</html>