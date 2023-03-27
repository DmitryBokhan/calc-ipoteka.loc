<?php

namespace iteush;

abstract class Model
{
    public  array $attributes = []; // сюда будем складывать данные из форм
    public  array $errors = []; // сюда будем складывать возможные ошибки (например при валидации)
    public array $rules = []; // массив правил валидации
    public array $labels = []; // тут будем указывать какое именно поле не прошло валидацию

    public function __construct()
    {
        Db::getInstance();
    }
}