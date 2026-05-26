<?php
session_start();
require_once 'db.php';

$database = new DataBase();

// получаем параметры фильтрации
$filter = $_GET['filter'] ?? 'all';
$specific_date = $_GET['specific_date'] ?? null;

// валидация даты с регулярным выражением
if ($specific_date) {
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $specific_date, $matches)) {
        $specific_date = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
    } elseif (preg_match('/^(\d{2})[.\/](\d{2})[.\/](\d{4})$/', $specific_date, $matches)) {
        $specific_date = $matches[3] . '-' . $matches[2] . '-' . $matches[1];
    } else {
        $specific_date = null;
    }
}

$tasks = $database->LoadTasksForTable($filter, $specific_date);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой календарь</title>
    <style>
        body { font-family: Andale Mono, monospace; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f5f5f5; }
        .completed { background-color: #e0e0e0; text-decoration: line-through; }
        .filters { margin-bottom: 20px; }
        .filters select, .filters input { margin-right: 10px; padding: 5px; }
        button { padding: 5px 10px; margin-right: 10px; cursor: pointer; }
        .message { padding: 10px; margin-bottom: 20px; color: green; border: 1px solid black; }
    </style>
</head>
<body>
    <h1>Мой календарь</h1>
    
    <?php if(isset($_SESSION['message'])): ?>
        <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <div class="filters">
        <form method="GET" action="index.php" style="display: inline;">
            <select name="filter" onchange="this.form.submit()">
                <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>Все задачи</option>
                <option value="current" <?php echo $filter == 'current' ? 'selected' : ''; ?>>Текущие задачи</option>
                <option value="overdue" <?php echo $filter == 'overdue' ? 'selected' : ''; ?>>Просроченные задачи</option>
                <option value="completed" <?php echo $filter == 'completed' ? 'selected' : ''; ?>>Выполненные задачи</option>
            </select>
        </form>
        
        <form method="GET" action="index.php" style="display: inline;">
            <input type="text" name="specific_date" placeholder="ДД.ММ.ГГГГ или ГГГГ-ММ-ДД" value="<?php echo htmlspecialchars($specific_date ?? ''); ?>">
            <button type="submit">Поиск по дате</button>
            <a href="index.php"><button type="button">Сбросить</button></a>
        </form>
    </div>
    
    <form method="POST" action="process.php">
        <input type="hidden" name="action" value="delete_complete" id="mainAction">
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;"><input type="checkbox" name="select_all"></th>
                    <th>ID</th>
                    <th>Тип</th>
                    <th>Тема</th>
                    <th>Место</th>
                    <th>Дата и время</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($tasks)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Задачи не найдены</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($tasks as $task): ?>
                        <tr class="<?php echo $task['status'] == 'completed' ? 'completed' : ''; ?>">
                            <td>
                                <input type="checkbox" name="task_ids[]" value="<?php echo $task['id']; ?>" 
                                       <?php echo $task['status'] == 'completed' ? 'disabled' : ''; ?>>
                            </td>
                            <td><?php echo $task['id']; ?></td>
                            <td><?php echo htmlspecialchars($task['type_name']); ?></td>
                            <td><a href="edit_task.php?id=<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['theme']); ?></a></td>
                            <td><?php echo htmlspecialchars($task['place'] ?? '-'); ?></td>
                            <td><?php echo $task['datetime'] ? date('d.m.Y H:i', strtotime($task['datetime'])) : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="action" value="complete">Выполнено</button>
            <button type="submit" name="action" value="delete" >Удалить выбранные</button>
            <a href="create_task.php"><button type="button" > Создать новую задачу</button></a>
        </div>
    </form>
</body>
</html>