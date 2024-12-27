<?php

$arResult = [];

define('CONST_PI', 3.14);

//require, include, require_once, include_once
require __DIR__ . '/req.php';

$arResult['E'] = CONST_E;

require __DIR__ . '/req2.php';
?>

