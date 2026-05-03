<?php
class FormReg{
    protected $themes = [
        1 => 'Бизнес',
        2 => 'Технологии',
        3 => 'Реклама и Маркетинг',
    ];

    protected $wallet = [
        1 => 'WebMoney',
        2 => 'Яндекс.Деньги',
        3 => 'PayPal',
        4 => 'кредитная карта',    
    ];

    public function __construct($postData = []){
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
            $this->data['agreement'] = 'no';
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
        $date = new DateTime();
        $subDate = $date->format('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? getenv('REMOTE_ADDR') ?? 'unknow';
        $record = [
            $this->escapeDelimiter($subDate),
            $this->escapeDelimiter($ip),
            $this->escapeDelimiter($this->data['name']),
            $this->escapeDelimiter($this->data['lastname']),
            $this->escapeDelimiter($this->data['phone']),
            $this->escapeDelimiter($this->data['email']),
            $this->escapeDelimiter($this->data['themes'] . ':' . $this->themes[$this->data['themes']]),
            $this->escapeDelimiter($this->data['wallet'] . ':' . $this->wallet[$this->data['wallet']]),
            $this->escapeDelimiter($this->data['agreement']),
            '0',
        ];
        $line = implode('|', $record) . "\n";

        if (!is_dir('./form')){
            mkdir('./form',0755,true);
        }

        return file_put_contents('./form/reg.csv', $line, FILE_APPEND | LOCK_EX);
    }

    //чтение заявок
    public static function ReadAll() : array {
        $csvFile = 'form/reg.csv';
        $apps = [];

        if (file_exists($csvFile)) {
            $lines = file($csvFile, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $index => $line) {
                if (trim($line) === '') continue;
                $data = explode('|', $line);
                if (count($data) < 10) {
                    $data[] = '0';
                }
                $apps[$index] = $data;
            }
        }
        
        return $apps;
    }

    public static function SaveReg($apps){
        $csvFile = 'form/reg.csv';
        $lines = [];

        foreach ($apps as $app) {
            $lines[] = implode('|', $app);
        }
        
        return file_put_contents($csvFile, implode("\n", $lines));
    }

    //удаление
    public static function DeleteApp($indexes){
        $allApps = self::ReadAll();
        foreach ($indexes as $index){
            if (isset($allApps[$index])) {
                $allApps[$index][count($allApps[$index]) - 1] = '1';
            }
        }

        return self::SaveReg($allApps);
    }
    //активныеы заявки
    public static function GetActiveApp() : array {
        $allApps = self::ReadAll();
        $activeReg = [];
        foreach ($allApps as $index => $app){
            $deleted = end($app) == '1';
            if (!$deleted){
                $displayData = array_slice($app, 0, -2);
                $activeReg[$index] = $displayData;
            }
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

