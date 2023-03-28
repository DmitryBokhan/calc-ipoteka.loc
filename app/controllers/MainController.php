<?php

namespace app\controllers;

use app\models\Main;
use iteush\Controller;
use iteush\DataGoogleTable;

//** @property Main $model */

class MainController extends Controller
{

    //$this->layout = 'something'; // так можно переопределить шаблон для всех экшинов сразу.

    public function indexAction()
    {

        //$this->layout = 'calc'; // так можно переопределить шаблон для экшина.

        // передаем метаданные
        $this->setMeta('Ипотечный калькулятор', 'Description...', 'Keywords...');

        $pars = DataGoogleTable::getCityData('krd');
        $cities = DataGoogleTable::getCitiesList();
        $banks = DataGoogleTable::getCityBanksList('krd');
        $progs = DataGoogleTable::getCityBankProgsList('krd', 'sber');
        $prog_items = DataGoogleTable::getCityBankProgItemsList('krd', 'sber', 'it');
        $discount = DataGoogleTable::getCityBankProgDiscount('krd', 'sber', 'gos', 0);

        //так передаются переменные в шаблон (через метод контроллера "set()")
        //$this->set(['test'=>'TEST', 'something'=>'Что-то еще...']);

        //передаем сразу несколько переменных через ф-ю compact

        $this->set(compact('cities', 'banks', 'progs', 'prog_items', 'discount', 'pars'));

    }

}