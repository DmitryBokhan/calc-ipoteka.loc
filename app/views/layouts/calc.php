<?php

use iteush\View;

/** @var $this View */
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= $this->getMeta() ?>
</head>
<body>
Шаблон калькулятора
<? debug($this->content) ?>
</body>
</html>
