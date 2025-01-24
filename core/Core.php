<?php

namespace Core;

class Core {
    public static function IncludeLocale(string $dir, string $temlateId) 
    {
        $lang = mb_language('ru');

        require($dir . '/' . $temlateId . '/lang/ru/template.php');
    }
    public static function getMessage($code) 
    {
        if(isset($MESS) && $MESS[$code] != '')
            return $MESS[$code];
    }
}