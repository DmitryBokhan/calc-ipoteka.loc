<?php

namespace iteush;

class Cache
{
    use TSingleton;

    /**
     * Записать в кеш
     * @param $key ключ кеша
     * @param $data данные для записи в кеш
     * @param integer $seconds время хранения данных
     * @return bool
     */
    public function set($key, $data, $seconds = 3600): bool
    {
        $content['data'] = $data;
        $content['end_time'] = time() + $seconds;
        if(file_put_contents(CACHE . '/' . md5($key) . '.txt', serialize($content))) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Получить данные из кеша по ключу
     * @param $key ключ к кешированному файлу
     * @return mixed|false
     */
    public function get($key)
    {
        $file = CACHE . '/' . md5($key) . '.txt';
        if(file_exists($file)){
            $content = unserialize(file_get_contents($file));
            if(time() <= $content['end_time'] ){
                return $content['data'];
            }
            unlink($file);
        }
        return false;
    }

    /**
     * Удалить данные из кеш
     * @param $key ключ к кешированному файлу
     * @return void
     */
    public function delete($key)
    {
        $file = CACHE . '/' . md5($key) . '.txt';
        if(file_exists($file)){
            unlink($file);
        }
    }
}