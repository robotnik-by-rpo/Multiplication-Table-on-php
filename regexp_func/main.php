<?php
    //1
    function TakeExtension($str) : string{
        $regexp = '/\.([^.]+)$/';
        preg_match($regexp, $str, $matches);
        return $matches[1] ?? '';
    }    

    //2-a
    function WhatTypeArchives($str) : bool{
        $archives = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2', 'xz', 'tgz', 'tbz', 'zst', 'iso', 'cab', 'arj', 'deb', 'rpm'];
        return in_array(TakeExtension($str),$archives);
    }

    //2-б
    function WhatTypeAudio($str) : bool{
        $audio = ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma', 'opus', 'alac', 'ape', 'aiff', 'amr', 'mid', 'midi', 'ra'];
        return in_array(TakeExtension($str),$audio);

    }

    //2-в
    function WhatTypeVideo($str) : bool{
        $video = ['mp4', 'avi', 'mkv', 'mov', 'wmv', 'flv', 'webm', 'm4v', 'mpg', 'mpeg', '3gp', 'ogv', 'ts', 'mts', 'm2ts', 'vob', 'rm', 'rmvb'];
        return in_array(TakeExtension($str),$video);
    
    }

    //2-г
    function WhatTypePicture($str) : bool{
        $images = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff', 'tif', 'svg', 'ico', 'heic', 'heif', 'raw', 'cr2', 'nef', 'arw', 'dng', 'psd', 'ai', 'eps'];
        return in_array(TakeExtension($str),$images);
    }

    //3
    function Title($file) : string{
        $lines = file_get_contents($file);
        $regexp = '/<title>(.*?)<\/title>/';
        if (preg_match($regexp, $lines, $matches)){
            return $matches[1];
        }

        return "";
    }

    //4
    function TagHref($file) : array{
        $lines = file_get_contents($file);
        $regexp = '/<a\s+(?:[^>]*?\s+)?href=["\']([^"\']*)["\']/i';
        preg_match_all($regexp, $lines, $matches);
        return $matches[1];
    }
    
    //5
    function TagImg($file) : array{
        $lines = file_get_contents($file);
        $regexp = '/<img\s+(?:[^>]*?\s+)?src=["\']([^"\']*)["\']/i';
        preg_match_all($regexp, $lines, $matches);
        return $matches[1];
    }

    //6
    function FindSubString($text, $findString) : string{
        $regexp = '/' . preg_quote($findString, '/') . '/i';
        return preg_replace($regexp, '<strong>$0</strong>', $text);
    }

    //7
    function Smile($text) : string{
         $patterns = [
             '/:\)/' => '<img src="smile.png" alt=":)">',
             '/;\)/' => '<img src="wink.png" alt=";)">',
             '/:\(/' => '<img src="sad.png" alt=":(">'
         ];
        
         return preg_replace(array_keys($patterns), array_values($patterns), $text);    

     }
    
    //8
    function TrimRegexp($line) : string{
        $regexp = '/\s+/';
        return preg_replace($regexp,' ',$line);
    }

    $task1 = TakeExtension('picture.png');
    echo '<br>1) picture.png: ' . $task1 . '</br>';

    
    $task2a = WhatTypeArchives('arc.zip');
    echo '<br>2-а) arc.zip: ' . $task2a . '</br>';

    $task2b = WhatTypeAudio('music.mp3');
    echo '<br>2-б) music.mp3: ' . $task2b . '</br>';

    $task2v = WhatTypeVideo('vid.mp4');
    echo '<br>2-в) vid.mp4: ' . $task2v . '</br>';

    $task2g = WhatTypePicture('pic.png');
    echo '<br>2-г) pic.png: ' . $task2g . '</br>';

    $task3 = Title('index.html');
    echo '<br>3) index.html: ' . $task3 . '</br>';

    $task4 = TagHref('index.html');
    echo '<br>4) index.html: ' . implode(', ', $task4) . '</br>';

    $task5 = TagImg('index.html');
    echo '<br>5) index.html: ' . implode(', ',$task5) . '</br>';

    $text6 = 'PHP - лучший язык для веб-разработки, но многие не любят PHP.';
    $sub6 = 'PHP';
    $task6 = FindSubString($text6,$sub6);
    echo '<br>6) ' . $task6 . '</br>';

    $task7 = Smile('Привет! Как дела :) У меня всё отлично ;)');
    echo '<br>7) ' . htmlspecialchars($task7) . '</br>';

    $task8 = TrimRegexp("Hello    World,    I'm  a   PHP   code");
    echo '<br>8) ' . $task8 . '</br>';
?>