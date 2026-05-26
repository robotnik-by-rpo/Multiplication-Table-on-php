<?php
session_start();
require_once 'db.php';

$database = new DataBase();

// парсинг даты с регулярными выражениями
function parseDate($dateStr) {
    if (empty($dateStr)) {
        return null;
    }
    
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateStr, $matches)) {
        return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
    } elseif (preg_match('/^(\d{2})[.\/](\d{2})[.\/](\d{4})$/', $dateStr, $matches)) {
        return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
    }
    
    return null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'create') {
        if (empty($_POST['theme']) || empty($_POST['type_id']) || empty($_POST['comment'])) {
            $_SESSION['message'] = 'Ошибка: Заполните все обязательные поля (Тема, Тип, Комментарий)';
            header('Location: create_task.php');
            exit;
        }
        
        $datetime = null;
        if (!empty($_POST['date'])) {
            $date = parseDate($_POST['date']);
            if ($date && !empty($_POST['time'])) {
                $datetime = $date . ' ' . $_POST['time'] . ':00';
            } elseif ($date) {
                $datetime = $date . ' 00:00:00';
            }
        }
        
        $data = [
            'type_id' => $_POST['type_id'],
            'theme' => $_POST['theme'],
            'place' => $_POST['place'] ?? '',
            'datetime' => $datetime,
            'duration' => $_POST['duration'] ?? null,
            'comment' => $_POST['comment']
        ];
        
        if ($database->AddNewTask($data)) {
            $_SESSION['message'] = 'Задача успешно добавлена!';
            header('Location: success.php');
        } else {
            $_SESSION['message'] = 'Ошибка при добавлении задачи';
            header('Location: create_task.php');
        }
        
    } elseif ($action == 'delete') {
        $ids = $_POST['task_ids'] ?? [];
        if (!empty($ids)) {
            if ($database->DelTask($ids)) {
                $_SESSION['message'] = 'Задачи успешно удалены';
            } else {
                $_SESSION['message'] = 'Ошибка при удалении задач';
            }
        } else {
            $_SESSION['message'] = 'Не выбраны задачи для удаления';
        }
        header('Location: index.php');
        
    } elseif ($action == 'complete') {
        $ids = $_POST['task_ids'] ?? [];
        if (!empty($ids)) {
            if ($database->CompleteTasks($ids)) {
                $_SESSION['message'] = 'Задачи отмечены как выполненные';
            } else {
                $_SESSION['message'] = 'Ошибка при выполнении задач';
            }
        } else {
            $_SESSION['message'] = 'Не выбраны задачи для выполнения';
        }
        header('Location: index.php');
        
    } elseif ($action == 'update') {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: index.php');
            exit;
        }
        
        $datetime = null;
        if (!empty($_POST['date'])) {
            $date = parseDate($_POST['date']);
            if ($date && !empty($_POST['time'])) {
                $datetime = $date . ' ' . $_POST['time'] . ':00';
            } elseif ($date) {
                $datetime = $date . ' 00:00:00';
            }
        }
        
        $data = [
            'type_id' => $_POST['type_id'],
            'theme' => $_POST['theme'],
            'place' => $_POST['place'] ?? '',
            'datetime' => $datetime,
            'duration' => $_POST['duration'] ?? null,
            'comment' => $_POST['comment'],
            'status' => $_POST['status'] ?? 'pending'
        ];
        
        if ($database->UpdateTask($id, $data)) {
            $_SESSION['message'] = 'Задача успешно обновлена';
        } else {
            $_SESSION['message'] = 'Ошибка при обновлении задачи';
        }
        header('Location: index.php');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action == 'delete_single') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($database->DelTask([$id])) {
                $_SESSION['message'] = 'Задача успешно удалена';
            } else {
                $_SESSION['message'] = 'Ошибка при удалении задачи';
            }
        }
        header('Location: index.php');
    }
}
?>