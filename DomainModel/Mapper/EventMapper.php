<?php 
namespace Mapper;

use Collections\Collection;
use DomainModel\EventModel;
use DomainModel\DomainModel;
use Collections\VenueCollection;

class EventMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    private $selectAllStmt;

    /**
     * Заранее подготавливаем запросы к БД
     */
    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = $this->pdo->prepare("SELECT * FROM some_event WHERE id=?");
        $this->updateStmt = $this->pdo->prepare("UPDATE some_event SET space=?, start=?, duration=?, name=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO some_event ( space, start, duration, name ) VALUES ( ?, ?, ?, ? )");

        $this->selectAllStmt = $this->pdo->prepare("SELECT * FROM some_event");
    }

    abstract public    function update(DomainModel $object);
    abstract protected function selectStmt(): \PDOStatement;

    protected function targetClass(): string
    {
        return EventModel::class;
    }

    public function getCollection(array $raw): Collection
    {
        return new VenueCollection($raw, $this);
    }

    /**
     * Поскольку Ивент находится в самом низу иерархии, коллекцию для него создвать пока не будем
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        $obj = new EventModel( 
                        (int)$raw['id'],
                        $raw['space'],
                        $raw['start'],
                        $raw['duration'],
                        $raw['name']
                    );
        return $obj;
    }

    /**
     * 
     */
    protected function doInsert(DomainModel $object)
    {
        //getName() рализован в VenueModel, возвращает имя места проведения
        $values = [$object->getName()];
        $this->insertStmt->execute($values);
        $id = $this->pdo->lastInsertId();
        $object->setId((int)$id);
    }

    public function update(DomainModel $object)
    {
        //Запросу надо 2 раза передать Id
        $values = [
            $object->getName(),
            $object->getId(),
            $object->getId()
        ] ;
        $this->updateStmt->execute($values);
    }

    /**
     * получить подготовленный оператор SELECT языка SQL
     */
    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    protected function selectAllStmt(): \PDOStatement
    {
        return $this->selectAllStmt;
    }
}
