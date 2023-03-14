<?php

function debug($data, $die = false)
{
    echo '<pre>' . print_r($data, 1) . '</pre>';
    if($die){
        die();
    }
}

/**
 * Вернуть результат работы функции htmlspecialchars()
 * @param string $str
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str);
}

/**
 * Сделать редирект.
 * Если не указан параметр http, то редирект происхдит на путь из константы PATH
 * @param $http
 * @return void
 */
function redirect($http = false)
{
    if($http) {
        $redirect = $http;
    }else{
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
    }
    header("Location: $redirect");
    die;
}

/**
 * Получить базовый url.
 * Возвращает url с учетом выбранного языка приложения
 * @return string
 */
function base_url()
{
    return PATH . '/' . (\iteush\App::$app->getProperty('lang') ? \iteush\App::$app->getProperty('lang') . '/': '');
}

/**
 * Получить GET параметр по ключу
 * @param string $key Ключ массива GET
 * @param string $type Значения 'i'-integer, 'f'-bool, 's'-string
 * @return float|int|string
 */
function get($key, $type = 'i')
{
    $param = $key;
    $$param = $_GET[$param] ?? '';
    if($type == 'i') {
        return (int)$$param;
    }elseif($type == 'f'){
        return (float)$$param;
    }else{
        return trim($$param);
    }
}

/**
 * Получить POST параметр по ключу
 * @param string $key Ключ массива POST
 * @param string $type Значения 'i'-integer, 'f'-bool, 's'-string
 * @return float|int|string
 */
function post($key, $type = 's')
{
    $param = $key;
    $$param = $_POST[$param] ?? '';
    if($type == 'i') {
        return (int)$$param;
    }elseif($type == 'f'){
        return (float)$$param;
    }else{
        return trim($$param);
    }
}


function __($key)
{
    echo \iteush\Language::get($key);
}


function ___($key)
{
    return \iteush\Language::get($key);
}

function get_cart_icon($id)
{
    if(!empty($_SESSION['cart']) && array_key_exists($id, $_SESSION['cart'])){
        $icon = '<i class="fas fa-luggage-cart"></i>';
    }else{
        $icon = '<i class="fas fa-shopping-cart"></i>';
    }
    return $icon;
}
