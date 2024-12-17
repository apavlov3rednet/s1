<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/Dbase.php');

use Core\Dbase;

class Install {

    public function __construct() {

    }

    public function run() : void
    {
        $installFile = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php');
        $sql = file_get_contents(__DIR__ . '/install.sql');
        
        echo '<pre>';
        print_r($installFile);
        echo '</pre>';

        $db = new Dbase($installFile);
        $db->runMethod($sql);
    }

    public function setDbParams(array $options): void {
        $setting = '<?php return [';

        if($options['host']) {
            $setting .= "'host' => '".$options['host']."',";
        }
            
        if($options['login']) {
            $setting .= "'user' => '".$options['login']."',";
        }

        if($options['password']) {
            $setting .= "'password' => '".$options['password']."',";
        }
        else {
            $setting .= "'password' => '',";
        }

        if($options['database']) {
            $setting .= "'database' => '".$options['database']."',";
        }

        $setting .= '];';

        $file = $_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php';
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