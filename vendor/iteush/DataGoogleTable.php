<?php

namespace iteush;

class DataGoogleTable
{

    /**
     * Преобразует данные из строки $str в нужный тип $type
     * @param string $str
     * @param string $type
     * @return bool|float|int|string|null
     */
    private static function prepareTableValue(string $str, string $type)
    {
        switch ($type){
            case 'str':
                $res = $str;
                break;
            case 'int':
                $str = str_replace(" ", "", $str);
                $res =  (int)$str;
                break;
            case 'bool':
                $res =  (bool)$str;
                break;
            case 'float':
                if($str != '' || is_numeric($str)){
                    $str = trim($str, "%");
                    $str = str_replace(",", ".", $str);
                }
                $res =  (float)$str;
                break;
            default:
                $res =  null;
                break;

        }
        return $res;
    }

    /**
     * Получить данные из google таблицы
     * @param $id - id таблицы
     * @param $gid - id листа
     * @param $range - диапазон получаемых данных (например: A1:B2, 3:3 - строка 3, B:B- столбец B, A:Y4 - все столбцы с A по Y начиная с 4 строки)
     * @return array
     */
    public static function getData($id, $gid, $range):array
    {
        $csv = file_get_contents('https://docs.google.com/spreadsheets/d/' . $id . '/export?format=csv&gid=' . $gid . '&range=' . $range);
        $csv = explode("\r\n", $csv);
        $array = array_map('str_getcsv', $csv);
        return $array;
    }

    /**
     * Обновить данные конкретного города
     * @param $city
     * @return void
     */
    public static function updateData($city){
        $map = require CONFIG . '/pars_map.php';
        $banks_name = require CONFIG . '/banks.php';
        $progs_name = require CONFIG . '/programs.php';

        foreach ($map[$city]['banks'] as $k_bank => $table_gid) {
            //массив с назаваниями переменных и типом данных
            $var_names = static::getData($map[$city]['table_id'], $table_gid, "3:3")[0];
            //получаем все записи программ банка
            $prog_items = static::getData($map[$city]['table_id'], $table_gid, "A:Z4");
            $res_arr['banks'][$k_bank] = [
                'name_ru' => $banks_name['en-ru'][$k_bank],
                'name_en' => $k_bank,
                'progs' => [],
            ];
            foreach ($prog_items as $progs){
                if(!in_array($progs_name['ru-en'][$progs[0]], array_keys($res_arr['banks'][$k_bank]['progs']))){
                    $res_arr['banks'][$k_bank]['progs'][$progs_name['ru-en'][$progs[0]]] = [
                        'name_ru' => $progs[0],
                        'name_en' => $progs_name['ru-en'][$progs[0]],
                    ];
                }
                $names = [];
                $prog_values = [];
                $i = 0;
                foreach ($var_names as $name_and_type){
                    list($name, $type) = explode("|", $name_and_type);
                    $names[] = trim($name);
                    $prog_values[] = static::prepareTableValue($progs[$i], trim($type));
                    $i++;
                }
                $res_arr['banks'][$k_bank]['progs'][$progs_name['ru-en'][$progs[0]]]['items'][] = array_combine($names , $prog_values);
            };
        };
        $res_arr['updated'] = date("Y-m-d H:i");
        $res_arr = json_encode($res_arr);
        file_put_contents(PARS. "/data_{$city}.json", $res_arr);
    }

    /**
     * Получить данные конкретного города
     * @param $city
     * @return array
     */
    public static function getCityData($city):array
    {
        $data = json_decode(file_get_contents(PARS. "/data_{$city}.json"), true);
        !empty($data) ?: static::updateData($city); // если файл пуст - обновляем данные
        return json_decode(file_get_contents(PARS. "/data_{$city}.json"), true);
    }

    /**
     * Получить массив названий всех городов
     * @return array
     */
    public static function getCitiesList():array
    {
        $cities = require CONFIG . "/cities.php";
        return $cities['en-ru'];
    }

    /**
     * Получить массив банков города
     * @param $city
     * @return array
     */
    public static function getCityBanksList($city):array
    {
        $city_data = static::getCityData($city);
        $banks = [];
        foreach ($city_data['banks'] as $bank){
            $banks[$bank['name_en']] = $bank['name_ru'];
        }
        return $banks;
    }

    /**
     * Получить массив названий программ банка в конкретном городе
     * @param $city
     * @param $bank
     * @return array
     */
    public static function getCityBankProgsList($city, $bank):array
    {
        $city_data = static::getCityData($city);
        $progs = [];
        foreach ($city_data['banks'][$bank]['progs'] as $prog){
            $progs[$prog['name_en']] = $prog['name_ru'];
        }
        return $progs;
    }

    /**
     * Получить массив дисконтов программы банка конкретного города
     * @param $city
     * @param $bank
     * @param $prog
     * @return array
     */
    public static function getCityBankProgItemsList($city, $bank, $prog):array
    {
        $city_data = static::getCityData($city);
        return $city_data['banks'][$bank]['progs'][$prog]['items'];
    }

    public static function getCityBankProgDiscount($city, $bank, $prog, $discount_num):array
    {
        $city_data = static::getCityData($city);
        return $city_data['banks'][$bank]['progs'][$prog]['items'][$discount_num];
    }

}