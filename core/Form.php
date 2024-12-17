<?php

use Dbase;

class Form extends Dbase {
    
    public function __construct() {
        parent::__construct();
    }

    private function uploadFile(): void {
        $files = $_FILES;

        //move_uploaded_file($from, $to);
    }

    public function save(): void {
        $this->uploadFile();
    }
}