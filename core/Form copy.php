<?php
require __DIR__ ."/Dbase.php";

use Core\Dbase;

class Form extends Dbase {
    private string $uploaddir;
    private array $arFiles;
    private array $arErrorFields;
    private array $arFields;
    private array $arStarList;
    
    public function __construct($starList = []) {
        parent::__construct();

        $this->uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/upload/voteform/';
        $this->arStarList = $starList;
    }
  
    private function uploadFile(): void {
        if(!empty($_FILES['FILE']) && is_array($_FILES['FILE']['name'])) {
            //Множественные файлы
            foreach($_FILES['FILE']['name'] as $key => $value) {
        
                if(!$this->checkFileType($_FILES['FILE']['type'][$key], 'image')) {
                    continue;   
                }
        
                $uploadfile = $this->uploaddir . $this->createFileName($value);
                if (move_uploaded_file($_FILES['FILE']['tmp_name'][$key], $uploadfile)) {
                    echo "Файл не содержит ошибок и успешно загрузился на сервер.\n";
                
                    $arFile = [
                        'upload_dir' => $uploadfile,
                        'type' => $_FILES['FILE']['type'][$key],
                        'size'=> $_FILES['FILE']['size'][$key],
                        'name'=> $_FILES['FILE']['name'][$key],
                        'full_path'=> $_FILES['FILE']['full_path'][$key],
                    ];
                    $this->arFiles[] = $this->registerUploadFile($arFile);
                }
            }
        }
        else {
            if(!empty($_FILES['FILE'])) {
             
            //Один файл
            if(!$this->checkFileType($_FILES['FILE']['type'], 'image')) {
                return;
            }
        
            $uploadfile = $this->uploaddir . $this->createFileName($_FILES['FILE']['name']);
        
            if (move_uploaded_file($_FILES['FILE']['tmp_name'], $uploadfile)) {
                echo "Файл не содержит ошибок и успешно загрузился на сервер.\n";
                $arFile = $_FILES['FILE'];
                $arFile['upload_dir'] = $uploadfile;
                $this->arFiles[] = $this->registerUploadFile( $arFile);
            }  
            }
        }
    }

    private function checkFileType(string $mime, string $accessType = 'image'): bool {
        $result = true;
        
        switch ($accessType) {
            case 'image':
                $result = in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
            break;
    
            case 'audio':
                $result = in_array($mime, ['audio/mp3','','']);
                break;
        }
        return $result;
    }

    private function createFileName($filename): string {
        $ext = explode('.', $filename); // array_key_last($ext)
        return md5($filename . rand(10,20) . time()) . '.' . end($ext);
    }

    private function registerUploadFile(array $file):int {
        $id = $this->add('b_files', $file);
    
        //статический метод DBase::add();
        //внутренний статический метод self::nameMethod();
        //инстанцирование $class = new Class();
        //вызов публичного метода $class->method();
        return $id;
    }

    private function prepareFields(): void {
        $POST = $_POST;

        $arTextFieldsKey = ['PREFERENCES', 'NEGATIVE', 'COMMENT', 'NOT_EQUALE_TEXT'];
        $arStarsFieldsKey = ['VOTE_NAME_FIELD']; //todo: переписать на динамический параметр

        foreach($POST as $key => $value) {
            if(in_array($key, $arTextFieldsKey)) {
                // if($key === 'PHONE') {
                //     $r = '/^\+\([0-9]{3}\)[0-9]{7}$/';
                //     preg_match($r, $value, $match);
                //     echo $match;
                // }

                $this->arFields[$key] = trim(stripslashes(htmlspecialchars($value)));
            }

            if(in_array($key, $arStarsFieldsKey)) {
                $this->arFields[$key] = (int)$value; 
                //(string), (int), (float) === string(), intval($), floatval()
            }
        }
    }

    private function showErrors() {

    }

    public function save(): mixed {
        $this->prepareFields();
        
        if(!empty($this->arErrorFields)) {
            $this->showErrors();
            return false; //произойдет остановка цикла
        }

        $this->uploadFile();
        $this->arFields['FILES'] = join(',', $this->arFiles);
        //implode(); explode();

        $id = $this->add('b_review', $this->arFields);
        return $id;
    }
}