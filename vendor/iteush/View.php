<?php

namespace iteush;

use RedBeanPHP\R;

class View
{
    /**
     * Контентная часть представления, которая передается в шаблон
     * @var string
     */
    public string $content = '';


    public function __construct(
        public $route,
        public $layout = '',
        public $view = '',
        public $meta = []
    )
    {
        if(false !== $this->layout){
            $this->layout = $this->layout ?: LAYOUT;
        }
    }

    /**
     * Отрисовать страницу
     * @param $data
     * @return void
     * @throws \Exception
     */
    public function render($data)
    {
        if(is_array($data)){
            extract($data); //создаем из массива переменные
        }
        $prefix = str_replace('\\', '/', $this->route['admin_prefix']);
        $view_file = APP . "/views/{$prefix}{$this->route['controller']}/{$this->view}.php";

        if(is_file($view_file)){
            ob_start();
            require_once $view_file;
            $this->content = ob_get_clean();
        }else{
            throw new \Exception("Не найден вид {$view_file}", 500);
        }

        if(false !== $this->layout){
            $layout_file = APP . "/views/layouts/{$this->layout}.php";
            if(is_file($layout_file)){
                require_once $layout_file;
            }else{
                throw new \Exception("Не найден шаблон {$layout_file}", 500);
            }
        }
    }

    /**
     * Получить заполненные мета теги и заголовок страницы
     * @return string
     */
    public function getMeta(): string
    {
        $separator = empty($this->meta['title'])?'':' :: ';
        $out = '<title>' . App::$app->getProperty('site-name') . $separator .  h($this->meta['title']) . '</title>' . PHP_EOL;
        $out .= '<meta name="description" content=' . h($this->meta['description']) . '">' . PHP_EOL;
        $out .= '<meta name="keywords" content=' . h($this->meta['keywords']) . '">' . PHP_EOL;

        return $out;
    }

    /**
     * Показать текущие запросы к БД
     * @return void
     */
    public function getDbLogs()
    {
        if(DEBUG){
            $logs = R::getDatabaseAdapter()
                ->getDatabase()
                ->getLogger();
            $logs = array_merge($logs->grep('SELECT'),
                $logs->grep('select'),
                $logs->grep('INSERT'),
                $logs->grep('insert'),
                $logs->grep('UPDATE'),
                $logs->grep('update'),
                $logs->grep('DELETE'),
                $logs->grep('delete'));
            debug($logs);
        }
    }

    /**
     * Подключить "часть" шаблона
     * @param $file
     * @param $data
     * @return void
     */
    public function getPart($file, $data = null)
    {
        if(is_array($data)) {
            extract($data);
        }
        $file = APP . "/views/{$file}.php";

        if(is_file($file)){
            require $file;
        }else{
            echo "Файл {$file} не найден ";
        }
    }
}