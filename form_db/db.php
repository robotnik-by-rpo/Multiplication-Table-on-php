<?php
class DataBase{
    protected $pdo;
    
    public function __construct(){
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=form", "webuser", "pass");
            $this->pdo -> exec("CREATE TABLE IF NOT EXISTS `subjects` (
                            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(255) NOT NULL,
                            PRIMARY KEY(`id`)
                            );

                            INSERT INTO `subjects` (`name`) VALUES
                            ('Бизнес и коммуникации'), 
                            ('Технологии'), 
                            ('Реклама'), 
                            ('Маркетинг'), 
                            ('Проектирование');

                            CREATE TABLE IF NOT EXISTS `payments` (
                            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(255) NOT NULL,
                            PRIMARY KEY(`id`)
                            );

                            INSERT INTO `payments` (`name`) VALUES
                            ('WebMoney'),
                            ('Яндекс.Деньги'),
                            ('PayPal'),
                            ('Кредитная карта'),
                            ('Робокасса');

                            CREATE TABLE IF NOT EXISTS `participants` (
                            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(255) NOT NULL,
                            `lastname` VARCHAR(255) NOT NULL,
                            `ip` VARCHAR(45) DEFAULT NULL,
                            `email` VARCHAR(255) NOT NULL,
                            `tel` VARCHAR(255) NOT NULL,
                            `subject_id` INT(10) NOT NULL,
                            `payment_id` INT(10) NOT NULL,
                            `mailing` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
                            `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                            `created_at` TIMESTAMP NOT NULL,
                            `updated_at` TIMESTAMP NOT NULL,
                            PRIMARY KEY(`id`),
                            INDEX `subject_id` (`subject_id`),
                            INDEX `payment_id` (`payment_id`),
                            INDEX `deleted_at` (`deleted_at`)
                            );");
        } catch (PDOException $e) {
            die("Ошибка подключения: " . $e->getMessage());
        }
    }

    // Добавить новую запись
    public function AddNewRec($data) : bool {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO participants 
                               (name, lastname, ip, email, tel, subject_id, payment_id, mailing, created_at, updated_at) 
                               VALUES 
                               (:name, :lastname, :ip, :email, :tel, :sub, :pay, :mailing, NOW(), NOW())");
            
            $stmt->execute([
                ":name" => $data['name'],
                ":lastname" => $data['lastname'], 
                ":ip" => $_SERVER['REMOTE_ADDR'] ?? null,
                ":email" => $data['email'],
                ":tel" => $data['phone'],
                ":sub" => (int)$data['themes'],
                ":pay" => (int)$data['wallet'], 
                ":mailing" => isset($data['agreement']) && $data['agreement'] == 'yes' ? 1 : 0
            ]);
            
            return true;
        } catch (PDOException $e) {
            error_log("Ошибка сохранения: " . $e->getMessage());
            return false;
        }
    }   
    
    //загрузка данных в админку
    public function LoadActiveRec() : array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.*, s.name as subject_name, pay.name as payment_name 
                FROM participants p
                LEFT JOIN subjects s ON p.subject_id = s.id
                LEFT JOIN payments pay ON p.payment_id = pay.id
                WHERE p.deleted_at IS NULL
                ORDER BY p.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Ошибка загрузки: " . $e->getMessage());
            return [];
        }
    }
    
    // удаление
    public function DelRec(array $ids) : bool{
        if (empty($ids)) {
            return false;
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->pdo->prepare("UPDATE participants SET deleted_at = NOW() WHERE id IN ($placeholders)");
            return $stmt->execute($ids);
        } catch (PDOException $e) {
            error_log("Ошибка удаления: " . $e->getMessage());
            return false;
        }
    }
}
?>