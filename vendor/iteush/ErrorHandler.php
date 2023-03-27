<?php

namespace iteush;

class ErrorHandler
{
    /**
     * Класс обработки ошибок
     */

    public function __construct()
    {
        // https://habr.com/ru/post/161483

        if(DEBUG){
            error_reporting(-1);
        }else{
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'errorHandler']);
        ob_start();
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $this->logError($errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if(!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            $this->logError($error['message'], $error['file'], $error['line']);
            ob_end_clean();
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        }else{
            ob_end_flush();
        }
    }

    /**
     * Принимаем объект исключения, записываем в лог и выводим ошибку
     * @param \Throwable $e
     * @return void
     */
    public function exceptionHandler(\Throwable $e)
    {
        $this->logError($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    /**
     * Записать ошибку в лог
     * @param $message
     * @param $file
     * @param $line
     * @return void
     */
    protected function logError($message ='', $file = '', $line ='')
    {
        file_put_contents(LOGS . '/errors.log', "[" . date('Y-m-d H:i:s'). "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n==============\n", FILE_APPEND);
    }

    /**
     * Вывести ошибку
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @param $responce
     * @return void
     */
    protected function displayError($errno, $errstr, $errfile, $errline, $responce = 500)
    {
        if($responce == 0){
            $responce = 404;
        }
        //отправляем ответ в заголовках
        http_response_code($responce);
        if ($responce == 404 && !DEBUG){
            require_once WWW . '/errors/404.php';
            die;
        }
        if(DEBUG) {
            require_once WWW . '/errors/development.php';
        }else{
            require_once WWW . '/errors/production.php';
        }
        die;
    }


}