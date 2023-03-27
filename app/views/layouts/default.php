<?php

use iteush\View;

/** @var $this View */
/** @var $data View */
/** @var $test View */
/** @var $something View */
?>

 <h1>Это шаблон по умолчанию</h1>

<? $this->getPart('parts/header'); ?>

<?= $this->content; ?>

<? $this->getPart('parts/footer'); ?>
