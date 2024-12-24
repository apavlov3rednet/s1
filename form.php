<?php
require __DIR__ . '/core/Form.php';

$form = new Form([]);

if(!empty($_POST)) {
    $form->save();
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