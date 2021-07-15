<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект EventModel
 */
namespace Mapper;

use Collections\Collection;
use DomainModel\EventModel;
use DomainModel\DomainModel;
use Collections\EventCollection;

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
     * Получить имя обрабатываемой этим маппером модели для проверки
     * Проверка в суперклассе Mapper
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return EventModel::class;
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
     * 
     * @return string
     */
    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    /**
     * получить подготовленный оператор SELECT языка SQL
     * Выборку производит метод DomainModel::findAll()
     * 
     * @return string
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
    protected function doUpdate(DomainModel $model)
    {
        //Получение данных из EventModel
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




//TODO: вероятно здесь должна лежить полная выборка данных их таблицы, в целях кеширования
    public function getCollection(array $raw): Collection
    {
        return new EventCollection($raw, $this);
    }
}
