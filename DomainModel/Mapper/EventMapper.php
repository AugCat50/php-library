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
    private $findBySpaceStmt;

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

        //Здесь внедрен еще один оператор, $findBySpaceStmt, предназначенный для 
        //выборки объектов типа Event, характерных для отдельного объекта типа Space.
        $this->findBySpaceStmt = $this->pdo->prepare("SELECT * FROM some_event where space=?");
    }

    /**
     * Создать объект модели соответствующей мапперу
     * 
     * Поскольку Ивент находится в самом низу иерархии, коллекцию для него создвать пока не будем
     * 
     * @return DomainModel\EventModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        $eventModel = new EventModel( 
                        (int)    $raw['id'],
                        (int)    $raw['space'],
                        (string) $raw['start'],
                        (int)    $raw['duration'],
                        (string) $raw['name']
                    );
        return $eventModel;
    }


    /**
     * получить подготовленный оператор SELECT языка SQL
     * Выборку производит метод DomainModel::find()
     */
    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    /**
     * получить подготовленный оператор SELECT языка SQL
     * Выборку производит метод DomainModel::findAll()
     */
    protected function selectAllStmt(): \PDOStatement
    {
        return $this->selectAllStmt;
    }

    /**
     * Операция добавления новой строки в таблицу event
     * 
     * @param DomainModel\EventModel $model
     * 
     * @return void
     */
    protected function doInsert(DomainModel $model)
    {
        //Получение данных из EventModel
        $values = [
            $model->getSpace(),
            $model->getStart(),
            $model->getDuration(),
            $model->getName()
        ];
        $this->insertStmt->execute($values);

        //Здесь происходит магия. Вне зависимости от того, какой был задан id у модели,
        //id будет задан автоматически, и записан в поле id модели
        $id = $this->pdo->lastInsertId();
        $model->setId((int)$id);
    }

    /**
     * Операция обновления строки в таблице event
     * 
     * @param DomainModel\EventModel $model
     * 
     * @return void
     */
    public function update(DomainModel $model)
    {
        //Запросу надо 2 раза передать Id
        $values = [
            $model->getSpace(),
            $model->getStart(),
            $model->getDuration(),
            $model->getName(),
            $model->getId(),
            $model->getId()
        ] ;
        $this->updateStmt->execute($values);
    }





//TODO: Дальше работа с коллекциями

    protected function targetClass(): string
    {
        return EventModel::class;
    }

    public function getCollection(array $raw): Collection
    {
        return new VenueCollection($raw, $this);
    }
}
