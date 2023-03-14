<?php

define("DEBUG", 1); // 0 - для продакшн
define("ROOT", dirname(__DIR__));
define("WWW", ROOT . '/public');
define("APP", ROOT . '/app');
define("CORE", ROOT . '/vendor/iteush');
define("HELPERS", ROOT . '/vendor/iteush/helpers');
define("CACHE", ROOT . '/tmp/cache');
define("LOGS", ROOT . '/tmp/logs');
define("CONFIG", ROOT . '/config');
define("LAYOUT", 'ishop'); //название шаблона по умолчанию
define("PATH", 'http://calc-ipoteka.loc');
define("ADMIN", 'http://calc-ipoteka.loc/admin');
define("NO_IMAGE", '/uploads/no_image.jpg');

require_once ROOT . '/vendor/autoload.php';


