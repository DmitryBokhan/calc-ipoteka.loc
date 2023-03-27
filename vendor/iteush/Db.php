<?php



namespace iteush;

use RedBeanPHP\R;

/**
 * Класс подключения к базе данных
 */
class Db
{
    use TSingleton;


    private function __construct()
    {
        $db = require_once CONFIG . '/config_db.php';
        R::setup($db['dsn'], $db['user'], $db['password']);
        if(!R::testConnection()){ //проверяем подключение, если не удалось подключить, выкидываем исключение
            throw  new \Exception('No connection to DB', 500);
        }
        R::freeze(true); // запрещаем модификацию схемы базы данных
        if(DEBUG){
            R::debug(true,3); // возвращаем все SQL запросы к базе в если включен режим отладки
        }
    }
}