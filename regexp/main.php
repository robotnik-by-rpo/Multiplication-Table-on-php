<?php
    $patterns = [
        'int' => '/^\d+$/',
        'Lat' => '/^[\da-zA-Z]+$/',
        'LatKi' => '/^[\da-zA-ZА-Яа-яёЕ]+$/u',
        'domain' => '/^[A-Za-z0-9][A-Za-z0-9-]{0,62}(\.[A-Za-z0-9][A-Za-z0-9-]{0,62})+$/',
        'nameUser' => '/^[A-Za-z][A-Za-z0-9]{2,24}$/',
        'passwordNumLat' => '/^[\da-zA-Z]+$/',
        'passwordNumLatS' => '/^[a-zA-Z\d!@#$%^&*()_=+-]{8,}$/',
        'date-' => '/^\d{4}\-(0[1-9]|1[0-2])\-(0[1-9]|[12]\d|3[01])$/',
        'date/' => '/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/',
        'date.' => '/^(0[1-9]|[12]\d|3[01])\.(0[1-9]|1[0-2])\.\d{4}$/',
        'timeFull' => '/^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/',
        'timeShort' => '/^([01]\d|2[0-3]):([0-5]\d)$/',
        'url' => '/^(http|https):\/\/([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\/.*)?$/',
        'email' => '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'ip4' => '/^((25[0-5]|2[0-4]\d|1\d{2}|\d{1,2})\.){3}(25[0-5]|2[0-4]\d|1\d{2}|\d{1,2})$/',
        'ip6' => '/^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$/',
        'mac' => '/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/',
        'phone' => '/^\+7\d{10}$/',
        'card' => '/^(\d{4}\s){3}\d{4}$/',
        'inn' => '/^(\d{10}|\d{12})$/',
        'postIndex' => '/^\d{6}$/',
        'priseRUB' => '/^\d+\,\d{2} ?руб\.$/',
        'priseUSD' => '/^\$\d+\.\d{2}$/',
    ];

    $datatest = [
        'int' => '3235',
        'Lat' => 'sAff',
        'LatKi' => 'ыпЫПыф',
        'domain' => 'google.com',
        'nameUser' => 'monke33',
        'passwordNumLat' => 'asfAD12',
        'passwordNumLatS' => 'Ad@325qB!',
        'date-' => '2006-11-12',
        'date/' => '11/12/2006',
        'date.' => '11.12.2006',
        'timeFull' => '12:35:45',
        'timeShort' => '12:35',
        'url' => 'http://yandex.ru/',
        'email' => 'user@maildomain.com',
        'ip4' => '94.137.192.81',
        'ip6' => '2001:0:9d38:6abd:c70:2d3c:a176:3398',
        'mac' => 'ec:23:3d:1b:7a:e7',
        'phone' => '+79021234567',
        'card' => '4048 4323 9889 3301',
        'inn' => '3808753981',
        'postIndex' => '664000',
        'priseRUB' => '2546,10 руб.',
        'priseUSD' => '$39.99',
        ];

    $i = 1;
    foreach($patterns as $key => $pattern){
        $testVal = $datatest[$key];
        $res = preg_match($pattern, $testVal) ? 'matched':'didn\'t match';
        echo "<br>$i $testVal: $res\n</br>";
        $i++;
    }
?>