<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/Dbase.php');

use Core\Dbase;

class Install {

    public function __construct() {

    }

    public function run() : void
    {
        $installFile = require($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php');
        $sql = file_get_contents(__DIR__ . '/install.sql');

        $db = new Dbase($installFile);
        $result = $db->runMethod($sql);

        if($result) {
            header("Location: /index.php?success=Y");
        }
        else {
            header("Location: /index.php?error=".$result);
        }
    }

    public function setDbParams(array $options): void {

        $setting = 'return [' . PHP_EOL;

        if($options['host']) {
            $setting .= "'host' => '".$options['host']."'," . PHP_EOL;
        }
            
        if($options['login']) {
            $setting .= "'user' => '".$options['login']."'," . PHP_EOL;
        }

        if($options['password']) {
            $setting .= "'password' => '".$options['password']."'," . PHP_EOL;
        }
        else {
            $setting .= "'password' => ''," . PHP_EOL;
        }

        if($options['database']) {
            $setting .= "'database' => '".$options['database']."'," . PHP_EOL;
        }

        $setting .= '];';

        $file = $_SERVER['DOCUMENT_ROOT'] . '/core/.settings.json';
        file_put_contents($file, $setting);
    }

    public function setBaseParams() {
        // __DIR__
        if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload');
        }
            
        if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/voteform/')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/voteform/');
        }
    }
}

$install = new Install();
$install->setDbParams($_POST);
$install->setBaseParams();
//$install->setAddParams();
$install->run();