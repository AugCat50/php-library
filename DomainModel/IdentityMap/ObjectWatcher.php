<?php
/**
 * Шаблон Identity Мар позволяет избежать дублирования объектов и излишняя их загрузка из БД
 * 
 * До тех пор, пока шаблон Identity Мар применяется во всех контекстах, 
 * где объекты формируются из базы данных или вводятся в нее, 
 * вероятность дублирования объектов в ходе одного процесса практически равна нулю
 * 
 * В данной системе используется Мапперами
 */
namespace IdentityMap;

use Traits\SingletonTrait;

use DomainModel\DomainModel;

class ObjectWatcher
{
    use SingletonTrait;

    private $all = [ ] ;
    private static $instance = null;

    // private function __construct()
    // {
    //     //
    // }

    // public static function instance(): self
    // {
    //     if (is_null(self::$instance)) {
    //         self::$instance = new ObjectWatcher();
    //     }
    //     return self::$instance;
    // }

    /**
     * Генерация уникального ключа объекта. 
     * Создаётся конкатинацией имени класса и Id
     * 
     * @return string
     */
    public function globalKey(DomainModel $model): string
    {
        $key = get_class($model) . "." . $model->getId();
        return $key;
    }

    /**
     * Сохранить ключ в массив и ссылку на объект
     */
    public static function add(DomainModel $model)
    {
        
        $inst = self::getInstance();
        $inst->all[$inst->globalKey($model)] = $model ;
    }

    /**
     * @param sring $classname
     * @param int   $id
     * 
     * @return null|object
     */
    public static function exists(string $classname, int $id)
    {
        $inst = self::getInstance();
        $key = "{$classname}.{$id}";

        if ( isset($inst->all[$key]) ) {
            return $inst->all[$key];
        }
        return null;
    }
}