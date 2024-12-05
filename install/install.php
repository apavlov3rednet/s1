<?php

use Dbase;

class Install {

    public function __construct() {

    }

    public function run() : void
    {
        $db = new Dbase();

        $installFile = file_get_contents(__DIR__ . '/install.sql');
        $db->runMethod($installFile);
    }

    public function setDbParams(array $options): void {
        $setting = '<?php return [';

        if($options['host']) {
            $setting .= "'host' => '".$options['host']."',";
        }
            
        if($options['user']) {
            $setting .= "'user' => '".$options['user']."',";
        }

        if($options['password']) {
            $setting .= "'password' => '".$options['password']."',";
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
$install->setDbParams([
    'host' => 'MySQL-8.0',
    'database' => 'groupseven',
    'login' => 'root',
    'password' => '',
]);
$install->setBaseParams();
//$install->setAddParams();
$install->run();