<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект
 */
namespace Mapper;

use Registry\Registry;
use DomainModel\DomainModel;

abstract class Mapper
{
    protected $pdo;
    protected $reg;

    public function __construct()
    {
        $this->reg = Registry::getInstance();
        $this->reg->setPdo();
        $this->pdo = $this->reg->getPdo();
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
        //Получить подготовленный оператор SELECT языка SQL в дочерней реализации и запустить на выполнение
        $this->selectStmt()->execute([$id]);

        //??
        $row = $this->selectStmt()->fetch();
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
    public function createObject(array $raw): DomainModel
    {
        $obj = $this->doCreateObject($raw);
        return $obj;
    }

    public function insert(DomainModel $obj)
    {
    $this->dolnsert($obj);
    }

    abstract public    function update(DomainModel $object);
    abstract protected function doCreateObject(array $raw): DomainModel;
    abstract protected function dolnsert(DomainModel $object);
    abstract protected function selectStmt(): \PDOStatement;
    abstract protected function targetclass(): string;
}
