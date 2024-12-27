
<?php
//C:\OSPanel\home\s1\upload\voteform
$uploaddir = $_SERVER['DOCUMENT_ROOT']. '/upload/voteform/';

echo '<pre>';
print_r($_FILES);
echo '</pre>';

function createFileName($filename): string {
    $ext = explode('.', $filename)[1];
    return md5($filename . rand(10,20) . time()) . '.' . $ext;
}

function checkFileType(string $mime, string $accessType = 'image'): bool {
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

function registerUploadFile(array $file):int {
    //$id = DBase::add('b_files', $file);

    return 10;
}

if(!empty($_FILES['FILE']) && is_array($_FILES['FILE']['name'])) {
    //Множественные файлы
    foreach($_FILES['FILE']['name'] as $key => $value) {

        if(!checkFileType($_FILES['FILE']['type'][$key], 'image')) {
            continue;
        }

        $uploadfile = $uploaddir . createFileName($value);
        if (move_uploaded_file($_FILES['FILE']['tmp_name'][$key], $uploadfile)) {
            echo "Файл не содержит ошибок и успешно загрузился на сервер.\n";
        
            $arFile = [
                'upload_dir' => $uploadfile,
                'type' => $_FILES['FILE']['type'][$key],
                'size'=> $_FILES['FILE']['size'][$key],
                'name'=> $_FILES['FILE']['name'][$key],
                'full_path'=> $_FILES['FILE']['full_path'][$key],
            ];
            registerUploadFile($arFile);
        
        } else {
            echo "Возможная атака на сервер через загрузку файла!\n";
        }
    }
}
else {

    if(!empty($_FILES['FILE'])) {
     
    //Один файл
    if(!checkFileType($_FILES['FILE']['type'], 'image')) {
        return;
    }

    $uploadfile = $uploaddir . createFileName($_FILES['FILE']['name']);

    if (move_uploaded_file($_FILES['FILE']['tmp_name'], $uploadfile)) {
        echo "Файл не содержит ошибок и успешно загрузился на сервер.\n";
        $arFile = $_FILES['FILE'];
        $arFile['upload_dir'] = $uploadfile;
        registerUploadFile($arFile);
    } else {
        echo "Возможная атака на сервер через загрузку файла!\n";
    }   
    }
}






?>


<form enctype='multipart/form-data' 
    class="form" 
    action="" 
    method="POST">
        <div class="form-area">

            <input type="text" name="test[]">
            <input type="text" name="test[]">
            <input type="text" name="test[]">

            <div class="file-upload">
                <input type="file" name="FILE[]" multiple="Y">
            </div>
            
            <button>Отправить</button>
        </div>
    </form>
