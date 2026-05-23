<?php

include 'db.php';
class FormReg{
    protected $themes = [
        1 => 'Бизнес и коммуникации',
        2 => 'Технологии',
        3 => 'Реклама',
        4 => 'Маркетинг',
        5 => 'Проектирование',
    ];

    protected $wallet = [
        1 => 'WebMoney',
        2 => 'Яндекс.Деньги',
        3 => 'PayPal',
        4 => 'Кредитная карта',
        5 => 'Робокасса',   
    ];

    protected $db;

    public function __construct(array $postData = []){

        $this->db = new DataBase();
        $this->data = $postData;
    }
    
    protected $errors = [];
    protected $data = [];
    protected $regexpPhone = '/^8\d{10}$/';
    protected $regexpEmail = '/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,}$/';

    protected function escapeDelimiter(string $data) : string{
        return str_replace('|', '\\' . '|', $data);
    }

    public function TakeData() : bool{
        $this->errors = [];
        $nessessory = 'Поле необходимо для заполнения! <br>';
        $error = 'Ошибка в заполнения поля <br>';

        if (empty($this->data['name'])){
            $this->errors['name'] = $nessessory;
        }
        if (empty($this->data['lastname'])) {
            $this->errors['lastname'] = $nessessory;
        }
        if (empty($this->data['email'])) {
            $this->errors['email'] = $nessessory;
        }
        elseif (!preg_match($this->regexpEmail, $this->data['email'])){
            $this->errors['email'] = $error;
        }

        if (empty($this->data['phone'])){
            $this->errors['phone'] = $nessessory;
        }
        elseif (!preg_match($this->regexpPhone, $this->data['phone'])){
            $this->errors['phone'] = $error;
        }
        if (empty($this->data['themes'])){
            $this->errors['themes'] = $nessessory;
        }
        if (empty($this->data['wallet'])) {
            $this->errors['wallet'] = $nessessory; 
        }

        if (empty($this->data['agreement'])) {
            $this->data['agreement'] = 0;
        }

        return empty($this->errors);
    }

    public function GetErrors() : array {
        return $this->errors;
    }

    
    public function GetData() : array{
        return $this->data;
    }

    public function Save() {
        return $this->db->AddNewRec($this->data);
    }


    //удаление
    public function DeleteApp($indexes){
        if (empty($indexes)) {
            return false;
        }
        return $this->db->DelRec($indexes);
    }
    //активныеы заявки
    public function GetActiveApp() : array {
        $records = $this->db->LoadActiveRec();
        
        $activeReg = [];
        foreach ($records as $record) {
            $activeReg[$record['id']] = [
                $record['id'],
                $record['created_at'],
                $record['ip'] ?? '-',
                $record['name'],
                $record['lastname'],
                $record['tel'],
                $record['email'],
                $record['subject_name'] ?? $record['subject_id'],
                $record['payment_name'] ?? $record['payment_id'],
                $record['mailing'] ? 'Да' : 'Нет'
            ];
        }
        return $activeReg;
    }

    public function GetThemes() {
        return $this->themes;
    }

    public function GetWallet() {
        return $this->wallet;
    }

}


?>

