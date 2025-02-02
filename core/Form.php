<?php
namespace Core;

require __DIR__ ."/Dbase.php";

use Core\Dbase;

class Form extends Dbase {
    private string $uploaddir;
    private array $arFiles;
    private array $arErrorFields;
    private array $arFields;
    private array $arStarList;
    private array $arStars;
    
    public function __construct(array $params, array $starList) {
        parent::__construct($params);

        $this->uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/upload/voteform/';
        $this->arStarList = $starList;
    }

    private function createFileName($filename):string {
        if($filename) {
            $ext = end(explode('.', $filename));
            return md5($filename . rand(10,20) . time()) . '.' . $ext;
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

    private function registerUploadFile(string $filepath, string $baseName, string $mime = '', int $size = 0):void {
        $arFile = [
            'FILE_PATH' => $filepath,
            'BASE_NAME' => $baseName,
            'TYPE'=>$mime,
            'SIZE' => $size
        ];
    
        $id = $this->add('b_files', $arFile);

        $this->arFiles[] = $id;
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
                    $arFile = [
                        'upload_dir' => $uploadfile,
                        'type' => $_FILES['FILE']['type'][$key],
                        'size'=> $_FILES['FILE']['size'][$key],
                        'name'=> $_FILES['FILE']['name'][$key],
                        'full_path'=> $_FILES['FILE']['full_path'][$key],
                    ];
                    $this->registerUploadFile($arFile['upload_dir'], $arFile['name'], $arFile['type'], $arFile['size']);
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
                $arFile = $_FILES['FILE'];
                $arFile['upload_dir'] = $uploadfile;
                $this->registerUploadFile($arFile['upload_dir'], $arFile['name'], $arFile['type'], $arFile['size']);
            }
            }
        }
    }

    private function prepareFields() {
        $POST = $_POST;

        $arTextFieldsKey = ['PREFERENCES', 'NEGATIVE', 'COMMENT', 'NOT_EQUALE_TEXT'];
        $arStarsFieldsKey = array_keys($this->arStarList);
        $arStars = [];

        foreach($POST as $key => $value) {
            if(in_array($key, $arTextFieldsKey)) {
                $this->arFields[$key] = trim(htmlspecialchars($value));
            }

            if(in_array($key, $arStarsFieldsKey)) {
                $this->arStars[$key] = (int)$value; //(string), (int), (float) === string(), intval($), floatval()
            }
        }
        $this->arFields['USER_ID'] = (int)$POST['USER_ID'];
        $this->arFields['ELEMENT_ID'] = (int)$POST['ELEMENT_ID'];
    }

    private function showErrors() {

    }

    public function save(): mixed {
        $totalMark = 0;
        $this->prepareFields();

        if(empty($this->arErrorFields) && !empty($_FILES)) {
            $this->uploadFile();
            $this->arFields['FILES'] = join(',', $this->arFiles);
        }
        
        if(!empty($this->arErrorFields)) {
            $this->showErrors();
            return false;
        }

        foreach($this->arStars as $code => $value) {
            $totalMark += $value; //каждый раз увеличивает на значение $value
        }

        $this->arFields['TOTAL_MARK'] = round($totalMark/count($this->arStars), 1);

        $id = $this->add('b_review', $this->arFields);

        if($id > 0 && !empty($this->arStars)) {
            foreach($this->arStars as $code => $value) {
                $this->add('b_review_props', [
                    'REVIEW_ID' => $id,
                    'MARK' => $value,
                    'CODE' => $code
                ]);
            }
            //вот здесь было бы
            //$this->update
        }
        
        return $id;
    }
}