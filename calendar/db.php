<?php
class DataBase{
    protected $pdo;
    
    public function __construct(){
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=form", "webuser", "pass");
            
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS type (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    PRIMARY KEY (id)
                );
                
                CREATE TABLE IF NOT EXISTS tasks (
                    id INT AUTO_INCREMENT NOT NULL,
                    type_id INT NOT NULL,
                    theme VARCHAR(255) NOT NULL,
                    place VARCHAR(255),
                    datetime DATETIME,
                    duration TIME,
                    comment TEXT NOT NULL,
                    status ENUM('pending', 'completed') DEFAULT 'pending',
                    time_of_addition TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    FOREIGN KEY (type_id) REFERENCES type(id) ON DELETE CASCADE
                );
            ");
            

            try {
                $this->pdo->exec("ALTER TABLE type ADD UNIQUE INDEX idx_unique_name (name)");
            } catch (PDOException $e) {
            }
            
            $this->pdo->exec("
                INSERT IGNORE INTO type(name) VALUES
                    ('Встреча'),
                    ('Звонок'),
                    ('Совещание'),
                    ('Дело');
            ");
            
        } catch (PDOException $e) {
            die("Ошибка подключения: " . $e->getMessage());
        }
    }

    // добавить новую задачу
    public function AddNewTask($data) : bool {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tasks 
                               (type_id, theme, place, datetime, duration, comment, status) 
                               VALUES 
                               (:type_id, :theme, :place, :datetime, :duration, :comment, 'pending')");
            
            return $stmt->execute([
                ":type_id" => (int)$data['type_id'],
                ":theme" => $data['theme'], 
                ":place" => $data['place'] ?? null,
                ":datetime" => $data['datetime'] ?? null,
                ":duration" => $data['duration'] ?? null,
                ":comment" => $data['comment'],
            ]);
        } catch (PDOException $e) {
            error_log("Ошибка сохранения: " . $e->getMessage());
            return false;
        }
    }   
    
    // данные для загрузки в таблицу с фильтрацией
    public function LoadTasksForTable($filter = 'all', $specific_date = null) : array {
        try {
            $sql = "
                SELECT ts.id, te.name as type_name, ts.theme, ts.place, ts.datetime, ts.status
                FROM tasks AS ts
                JOIN type AS te ON te.id = ts.type_id
            ";
            
            $params = [];
            
            if ($filter == 'current') {
                $sql .= " WHERE ts.datetime >= NOW() AND ts.status = 'pending'";
            } elseif ($filter == 'overdue') {
                $sql .= " WHERE ts.datetime < NOW() AND ts.status = 'pending'";
            } elseif ($filter == 'completed') {
                $sql .= " WHERE ts.status = 'completed'";
            } elseif ($specific_date) {
                $sql .= " WHERE DATE(ts.datetime) = :specific_date";
                $params[':specific_date'] = $specific_date;
            }
            
            $sql .= " ORDER BY ts.datetime ASC, ts.time_of_addition DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Ошибка загрузки: " . $e->getMessage());
            return [];
        }
    }
    
    // удаление
    public function DelTask(array $ids) : bool{
        if (empty($ids)) {
            return false;
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id IN ($placeholders)");
            return $stmt->execute($ids);
        } catch (PDOException $e) {
            error_log("Ошибка удаления: " . $e->getMessage());
            return false;
        }
    }

    // Получить запись 
    public function GetTask($id) : array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ts.id, ts.type_id, te.name as type_name, ts.theme, ts.place, 
                       ts.datetime, ts.duration, ts.comment, ts.status
                FROM tasks AS ts
                JOIN type AS te ON te.id = ts.type_id
                WHERE ts.id = :id
            ");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : [];
        } catch (PDOException $e) {
            error_log("Ошибка загрузки: " . $e->getMessage());
            return [];
        }
    }
    
    // Обновить задачу
    public function UpdateTask($id, $data) : bool {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE tasks 
                SET type_id = :type_id, 
                    theme = :theme, 
                    place = :place, 
                    datetime = :datetime, 
                    duration = :duration, 
                    comment = :comment,
                    status = :status
                WHERE id = :id
            ");
            
            return $stmt->execute([
                ":id" => $id,
                ":type_id" => (int)$data['type_id'],
                ":theme" => $data['theme'],
                ":place" => $data['place'] ?? null,
                ":datetime" => $data['datetime'] ?? null,
                ":duration" => $data['duration'] ?? null,
                ":comment" => $data['comment'],
                ":status" => $data['status']
            ]);
        } catch (PDOException $e) {
            error_log("Ошибка обновления: " . $e->getMessage());
            return false;
        }
    }
    
    // Получить все типы задач
    public function GetTypes() : array {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM type ORDER BY id");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Ошибка загрузки типов: " . $e->getMessage());
            return [];
        }
    }

    public function CompleteTasks(array $ids) : bool {
        if (empty($ids)) {
            return false;
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->pdo->prepare("UPDATE tasks SET status = 'completed' WHERE id IN ($placeholders)");
            return $stmt->execute($ids);
        } catch (PDOException $e) {
            error_log("Ошибка выполнения: " . $e->getMessage());
            return false;
        }
    }
}
?>