
<pre><?print_r($_FILES)?></pre>

<?php

$uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/upload/voteform/';

function createFileName($filename):string {
    return md5($filename . rand(10, 20));
}

function registerUploadFile(string $filepath, string $baseName, string $mime = '', int $size = 0):int {
    $arFile = [
        'filepath' => $filepath,
        'baseName' => $baseName,
        'type'=>$mime,
        'size' => $size
    ];
    //Должны сохранить в БД

    return 10; //ID добавленного элемента в БД
}

function validateFileType(string $type, array|string $successArray):bool {
    return in_array($type, $successArray);
}

if(is_array($_FILES['FILE']['name'])) {

    foreach($_FILES['FILE']['name'] as $key => $value) {
        $ext = explode('.', $value)[1];
        $randName = createFileName($value).'.' . $ext;

        $uploadfile = $uploaddir . basename($randName);

        if(!validateFileType($_FILES['FILE']['type'][$key], ['image/jpeg', 'image/png', 'image/gif'])) {
            return false;
        }

        if (move_uploaded_file($_FILES['FILE']['tmp_name'][$key], $uploadfile)) {
            registerUploadFile($uploadfile, $value, $_FILES['FILE']['type'][$key], $_FILES['FILE']['size'][$key] );
        }
        else {
            echo "Возможная атака на сервер через загрузку файла!\n";
        }
    }

}
else {
    $ext = explode('.', $_FILES['FILE']['name'])[1];
    $randName = createFileName($value) . $ext;

    $uploadfile = $uploaddir . basename($randName);

    if(!validateFileType($_FILES['FILE']['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
        return false;
    }

    if (move_uploaded_file($_FILES['FILE']['tmp_name'], $uploadfile)) {
        registerUploadFile($uploadfile, $_FILES['FILE']['name'], $_FILES['FILE']['type'], $_FILES['FILE']['size']);
    }
    else {
        echo "Возможная атака на сервер через загрузку файла!\n";
    }
}



?>

<form enctype='multipart/form-data' 
    action="" 
    method="post">

    <div class="file-upload">
        <input type="file" name="FILE[]" multiple="Y"/>
    </div>

    <button>Отправить</button>
</form>