<?php
/**
 * Шаблон Identity Мар позволяет избежать дублирования объектов и излишней их загрузки из БД
 * 
 * До тех пор, пока шаблон Identity Мар применяется во всех контекстах, 
 * где объекты формируются из базы данных или вводятся в нее, 
 * вероятность дублирования объектов в ходе одного процесса практически равна нулю
 * 
 * 
 * Шаблон Unit of Work очень полезен, но имеется ряд моментов, которые следует иметь в виду. Применяя данный шаблон, следует быть уверенным, что во
 * всех операциях изменения на самом деле модифицированный объект помечается
 * как “измененный”. Невыполнение этого правила приведет к появлению ошибок,
 * обнаружить которые будет очень трудно.
 */
namespace DomainObjectAssembler\IdentityMap;

use DomainObjectAssembler\Traits\SingletonTrait;

use DomainObjectAssembler\DomainModel\DomainModel;

class ObjectWatcher
{
    use SingletonTrait;

    private        $all      = [ ] ;
    private static $instance = null;
    private        $dirty    = [];
    private        $new      = [];
    private        $delete   = []; //пока не используется

    /**
     * Генерация уникального ключа объекта. 
     * Создаётся конкатинацией имени класса и Id
     * 
     * @return string
     */
    public function globalKey(DomainModel $model): string
    {
        // if($model->getId() > 0)
        $key = get_class($model) . "." . $model->getId();
        return $key;
    }

    /**
     * Сохранить ключ в массив и ссылку на объект
     * 
     * @return void
     */
    public static function add(DomainModel $model)
    {
        
        $inst = self::getInstance();
        $inst->all[$inst->globalKey($model)] = $model ;
    }

    /**
     * Поиск объекта по уникальному ключу в массиве
     * 
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




    //Далее следует реализация Unit of Work

    /**
     * Добавить объект в массив на удаление
     */
    public static function addDelete(DomainModel $obj)
    {
        $inst = self::getInstance();
        $inst->delete[$inst->globalKey($obj)] = $obj ;
    }

    /**
     * Объекты помечаются как “измененные”, если они были модифицированы
     * после извлечения из базы данных. Измененный объект сохраняется с помощью
     * метода addDirtyf) в массиве свойств $dirty, до тех пор, пока не придет
     * время обновить базу данных.
     * 
     * @param DomainModel\DomainModel $model
     * 
     * @return void
     */
    public static function addDirty(DomainModel $model)
    {
        $inst = self::getInstance();

        //Если объекта нет в массиве new, сохраяем в массив dirty
        if (! in_array($model, $inst->new, true)) {
            $inst->dirty[$inst->globalKey($model)] = $model ;
        }
    }

    /**
     * Вновь созданные объекты вводятся в массив new
     * Объекты вводятся из этого массива в базу данных по намеченному плану.
     * 
     * @param DomainModel\DomainModel $model
     * 
     * @return void
     */
    public static function addNew(DomainModel $model)
    {
        $inst = self::getInstance();
        // идентификатор пока еще отсутствует
        $inst->new[] = $model;
    }

    /**
     * В клиентском коде может быть по собственным причинам решено, что измененный объект не должен подвергаться обновлению.
     * С этой целью измененный объект может быть помечен в клиентском коде как “сохраненный” с помощью метода addClean()
     *
     * @param DomainModel\DomainModel $model
     * 
     * @return void
     */
    public static function addClean(DomainModel $model)
    {
        $inst = self::getInstance();
        unset ($inst->delete[$inst->globalKey($model)]) ;
        unset ($inst->dirty [$inst->globalKey($model)]);

        $inst->new = array_filter(
                        $inst->new, 
                        function ($a) use ($model) {
                            return !($a === $model);
                        }
                    );
    }

    /**
     * Когда наконец приходит время обработать все объекты, сохраненные в
     * массивах, должен быть вызван метод performOperations() (вероятно, из
     * класса контроллера или его вспомогательного класса). Этот метод обходит в
     * цикле массивы $dirty и $new, обновляя или добавляя объекты.
     * 
     * Подозреваю, где-то тут можно реализовать механизм транзакций.
     */
    public function performOperations()
    {
        foreach ($this->dirty as $key => $obj) {
            // $obj->getFinder()->update($obj);
            $obj->getAssembler()->doUpdate($obj);

            //Служеюное сообщение для тестирования
            print "ObjectWather(160): Выполяется обновление в БД: " . $obj->getName() . "<br>";
        }

        foreach ($this->new as $key => $obj) {
            // $obj->getFinder()->insert($obj);
            $obj->getAssembler()->doInsert($obj);

            //Служеюное сообщение для тестирования
            print "ObjectWather(168): Выполяется сохранение в БД: " . $obj->getName() . "<br>";
        }

        //Так же сделать обход массива delete
        foreach ($this->delete as $key => $obj) {
            $obj->getAssembler()->doDelete($obj);

            //Служеюное сообщение для тестирования
            print "ObjectWather(176): Выполяется удаление в БД: " . $obj->getName() . "<br>";
        }
        
        //Удалить из массива all все модели, подвергшиеся удалению из БД
        $inst = self::getInstance();
        foreach ($this->delete as $key => $obj){
            unset( $this->all[$inst->globalKey($obj)]);
        }

        $this->dirty  = [];
        $this->new    = [];
        $this->delete = [];
    }
}