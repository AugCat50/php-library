<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект EventModel
 */
namespace DomainObjectAssembler\Mapper;

use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainModel\EventModel;
use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\Collections\EventCollection;
use DomainObjectAssembler\Collections\DeferredEventCollection;
use DomainObjectAssembler\DomainObjectFactory\DomainObjectFactory;
use DomainObjectAssembler\DomainObjectFactory\EventObjectFactory;

class EventMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    private $selectAllStmt;
    private $findBySpaceStmt;

    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = $this->pdo->prepare("SELECT * FROM some_event WHERE id=?");
        $this->updateStmt = $this->pdo->prepare("UPDATE some_event SET space=?, start=?, duration=?, name=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO some_event ( space, start, duration, name ) VALUES ( ?, ?, ?, ? )");

        $this->selectAllStmt = $this->pdo->prepare("SELECT * FROM some_event");

        //Оператор $findBySpaceStmt, предназначен для выборки объектов типа Event, характерных для отдельного объекта типа Space.
        $this->findBySpaceStmt = $this->pdo->prepare("SELECT * FROM some_event where space=?");
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
            $model->getSpace()->getId(),
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
    protected function doUpdate(DomainModel $model)
    {
        //Получение данных из EventModel
        //Запросу надо 2 раза передать Id
        $values = [
            $model->getSpace()->getId(),
            $model->getStart(),
            $model->getDuration(),
            $model->getName(),
            $model->getId(),
            $model->getId()
        ] ;
        $this->updateStmt->execute($values);
    }

    /**
     * Выбрать из таблицы event все строки у которых поле space = $vid
     * И создать коллекцию с этими данными
     * 
     * По сути, выбирает все подчинённые объекты Event объекту Space
     * 
     * @return Collections\EventCollection
     */
    public function findBySpace($vid): Collection
    {
        $this->findBySpaceStmt->execute([$vid]);

        //Коллекции необходимо передать массив с данными и объект маппер, для создания из данных объектов
        //PDOStatement::fetchAll — Возвращает массив, содержащий все строки результирующего набора 
        return new EventCollection( $this->findBySpaceStmt->fetchAll(), $this );
    }

    /**
     * 
     */
    public function findBySpaceId(int $spaceId)
    {
        //Объект маппер
        //подготовленный SQL оператор
        //Массив параметров нужных SQL оператору (в данном случае один)
        return new DeferredEventCollection (
                                        $this,
                                        $this->findBySpaceStmt,
                                        [$spaceId]
                                    );
    }





    //Далее идут служебные методы
    /**
     * Служебный метод.
     * Получить подготовленный оператор SELECT языка SQL 
     * Выборку производит метод суперкласса Mapper::find()
     * 
     * @return \PDOStatement
     */
    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    /**
     * Служебный метод.
     * Получить подготовленный оператор SELECT языка SQL
     * Выборку производит метод суперкласса Mapper::findAll()
     *
     * @return \PDOStatement
     */
    protected function selectAllStmt(): \PDOStatement
    {
        return $this->selectAllStmt;
    }

    /**
     * Служебный метод.
     * Получить имя обрабатываемой этим маппером модели для проверки. Проверка в суперклассе
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return EventModel::class;
    }

    /**
     * Служебный метод. 
     * Вызывается в конструкторе сперкласса. Возвращает объект фабрики моделей
     * 
     * @return DomainObjectFactory\EventObjectFactory
     */
    protected function getFactory(): DomainObjectFactory
    {
        return new EventObjectFactory();
    }











//TODO: вероятно здесь должна лежить полная выборка данных их таблицы, в целях кеширования
    public function getCollection(array $raw): Collection
    {
        return new EventCollection($raw, $this);
    }
}
