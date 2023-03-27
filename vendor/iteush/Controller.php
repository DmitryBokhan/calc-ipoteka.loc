<?php

namespace iteush;

abstract class Controller
{
    public array $data = []; //тут будут данные для передачи в представление
    public array $meta = ['title' => '', 'keywords' => '', 'description' => ''];
    public false|string $layout = ''; //если используется шаблон, то название храним тут
    public string $view = '';
    public object $model;

    public function __construct(public $route = [])
    {

    }

    /**
     * Создать объект модели
     * @return void
     */
    public function getModel()
    {
        $model = 'app\models\\' . $this->route['admin_prefix'] . $this->route['controller'];
        if (class_exists($model)) {
            $this->model = new $model();
        }
    }

    /**
     * Записать название вьюхи в текущий объект контроллера
     * @return void
     * @throws \Exception
     */
    public function getView()
    {
        $this->view = $this->view ?: $this->route['action'];
        (new View($this->route, $this->layout, $this->view, $this->meta))->render($this->data);
    }

    /**
     * Записать данные в объект контроллера
     * @param $data
     * @return void
     */
    public function set($data)
    {
        $this->data = $data;
    }

    /**
     * Записать метаданные в массив meta текущего объекта контроллера
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @return void
     */
    public function setMeta(string $title = '', string $description = '', string $keywords = '')
    {
        $this->meta = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
        ];
    }

    /**
     * Запрос был отправлен через ajax?
     * @return bool
     */
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }


    /**
     * Подключить шоблон
     * @param $view
     * @param $vars
     * @return void
     */
    public function loadView($view, $vars = [])
    {
        extract($vars);
        $prefix = str_replace('\\', '/', $this->route['admin_prefix']);
        require  APP . "/views/{$prefix}{$this->route['controller']}/{$view}.php";
        die;
    }
}