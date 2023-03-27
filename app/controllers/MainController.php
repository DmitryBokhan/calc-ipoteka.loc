<?php

namespace app\controllers;

use app\models\Main;
use iteush\Controller;

//** @property Main $model */

class MainController extends Controller
{

    //$this->layout = 'something'; // так можно переопределить шаблон для всех экшинов сразу.

    public function indexAction()
    {

        //$this->layout = 'calc'; // так можно переопределить шаблон для экшина.


        // передаем метаданные
        $this->setMeta('Ипотечный калькулятор', 'Description...', 'Keywords...');

        //так передаются переменные в шаблон (через метод контроллера "set()")
        //$this->set(['test'=>'TEST', 'something'=>'Что-то еще...']);

        //передаем сразу несколько переменных через ф-ю compact
        $prog = ['gos', 'it', 'female'];
        $test = 'TEST';
        $something = 'Что-то еще...';
        $this->set(compact('prog', 'test', 'something'));

    }

}