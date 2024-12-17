<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php if(isset($_GET['success'])):?>
    Модуль успешно установлен

    <?php
    //Удалить файл с сервера после успешной установки
    //unlink('/install/install.php');
    ?>
<?php endif;?>

<?php if(isset($_GET['error'])):?>
    Ошибка установки модуля: <?=$_GET['error']?>
<?php endif;?>

<form action="/install/install.php" method="POST">
    <label><span>host</span>
    <input type="text" name="host">
</label>
    
<label><span>database</span>
    <input type="text" name="database">
</label>
    
<label><span>login</span>
    <input type="text" name="login">
</label>
    
<label><span>password</span>
    <input type="password" name="password">
</label>

    <button>Отправить</button>
</form>
    
</body>
</html>