<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект
 * 
 * В базовом классе сосредоточены общие функциональные средства, 
 * а обязанности по выполнению операций над конкретными объектами делегируются дочерним классам
 */
namespace Mapper;

use Registry\Registry;
use Collections\Collection;
use DomainModel\DomainModel;
use DomainObjectFactory\DomainObjectFactory;

// use DomainModel\VenueModel;
// use IdentityMap\ObjectWatcher;
// use DomainObjectFactory\EventObjectFactory;
// use DomainObjectFactory\SpaceObjectFactory;
// use DomainObjectFactory\VenueObjectFactory;

abstract class Mapper
{
    protected $pdo;
    protected $reg;
    protected $factory = null;

    // abstract protected function doCreateObject(array $raw): DomainModel;
    abstract protected function doInsert(DomainModel $model);
    abstract protected function doUpdate(DomainModel $model);
    abstract protected function selectStmt(): \PDOStatement;
    abstract protected function targetClass(): string;

    abstract protected function getFactory(): DomainObjectFactory;

    /**
     * Абстрактные методы getCollection() и selectAllStmt(), чтобы все объекты типа Mapper могли
     * возвращать коллекцию, содержащую их постоянно сохраняемые объекты предметной области. 
     */
    abstract protected function selectAllStmt(): \PDOStatement;
    abstract protected function getCollection(array $raw): Collection;

    public function __construct()
    {
        $this->reg = Registry::getInstance();
        $this->reg->setPdo();
        $this->pdo = $this->reg->getPdo();

        //Получить объект фабрики для генерации объектов и работы с ObjectWatcher
        $this->factory = $this->getFactory();
    }

    //Можно создавать объект Mapper в реестре, в таком случае, конструктор может выглядеть так
    // function __construct(\PDO $pdo)
    // {
    //     $this->pdo = $pdo;
    // }

    /**
     * Метод find() отвечает за вызов подготовленного оператора, предоставляемого реализующим дочерним классом, 
     * а также за извлечение информации из строки базы данных.
     */
    public function find(int $id): DomainModel
    {
        //Сначала проверить наличие ссылки на объект в ObjectWatcher (IdentityMap)
        //Если таковой уже имеется, возвращаем, если нет, идём дальше
        $old = $this->factory->getFromMap($id);
        if (! is_null($old)) {
            return $old;
        }

        //Получить подготовленный оператор SELECT языка SQL в дочерней реализации и запустить на выполнение
        $this->selectStmt()->execute([$id]);

        /**
         * PDOStatement::fetch — Извлечение следующей строки из результирующего набора
         * @see https://www.php.net/manual/ru/pdostatement.fetch.php
         */
        $row = $this->selectStmt()->fetch();

        /**
         * PDOStatement::closeCursor — освобождает соединение с сервером, давая возможность запускать другие SQL-запросы.
         * @see https://www.php.net/manual/ru/pdostatement.closecursor.php
         */
        $this->selectStmt()->closeCursor();

        if (! is_array($row)) {
            return null;
        }

        if (! isset($row['id'])) {
            return null;
        }

        //Если всё пройдёт нормально, будет вызван doCreateObject() дочерней реализации
        $object = $this->createObject($row);
        return $object;
    }

    /**
     * метод createObject(), делегирует свои полномочия дочерней реализации
     */
    // public function createObject(array $raw): DomainModel
    // {
    //     //Сначала проверить наличие ссылки на объект в ObjectWatcher (IdentityMap)
    //     //Если таковой уже имеется, возвращаем, если нет, идём дальше
    //     $old = $this->getFromMap($raw['id']);
    //     if (! is_null($old)) {
    //         return $old;
    //     }

    //     //Создать объект
    //     $obj = $this->doCreateObject($raw);

    //     //Сохранить ссылку на объект в ObjectWatcher (IdentityMap)
    //     $this->addToMap($obj);
    //     return $obj;
    // }


    public function insert(DomainModel $model)
    {
        //Получаем имя класса в дочерней реализации
        $class = $this->targetClass();
        
        //Проверяем, что модель нужного класса
        if (! ($model instanceof $class)) {
            throw new \Exception("Мапперу должен быть передан объект типа {$class}");
        }

        $this->doInsert($model);

        //Сохранить  ссылку на объект в ObjectWatcher (IdentityMap)
        $this->factory->addToMap($model);
    }

    public function update(DomainModel $model)
    {
        //Получаем имя класса в дочерней реализации
        $class = $this->targetClass();

        //Проверяем, что модель нужного класса
        if (! ($model instanceof $class)) {
            throw new \Exception("Мапперу должен быть передан объект типа {$class}");
        }

        $this->doUpdate($model);
    }

    public function createObject(array $raw): DomainModel
    {
        $model = $this->factory->createObject($raw);
        return $model;
    }

    // /**
    //  * Блок общения с ObjectWatcher (IdentityMap)
    //  * 
    //  * В данном классе предусмотрены два удобных метода — addToMap() и getFromMap(). 
    //  * Это дает возможность не запоминать полный синтаксис статического обращения к классу ObjectWatcher
    //  * 
    //  * @param int $id
    //  * 
    //  * @return null|object
    //  */
    // private function getFromMap(int $id)
    // {
    //     return ObjectWatcher::exists( $this->targetClass(), $id );
    // }

    // /**
    //  * @see getFromMap()
    //  * 
    //  * @param DomainModel $model
    //  * 
    //  * @return null
    //  */
    // private function addToMap(DomainModel $model)
    // {
    //     ObjectWatcher::add($model);
    // }














    /**
     * В данном методе вызывается дочерний метод selectAllStmt (). 
     * Как и метод selectStmt (), он должен содержать подготовленный оператор SQL, по
     * которому из таблицы выбираются все строки.
     * 
     * TODO: пока не реализовано
     */
    public function findAll(): Collection
    {
        //TODO: надо проверить, кажется ничему не присваивается
        $this->selectAllStmt()->execute([]);

        //вызывается еще один новый метод getCollection(), которому передаются обнаруженные данные
        return $this->getCollection( $this->selectAllStmt()->fetchAll() ) ;
    }
}
