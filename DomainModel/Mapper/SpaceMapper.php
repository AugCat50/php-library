<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект SpaceModel
 * 
 * если встанет вопрос эффективности, то следует полностью реорганизовать исходный код класса SpaceMapper 
 * и получить все необходимые данные за один проход с помощью конструкции JOIN SQL-запроса
 */
namespace Mapper;

use Collections\Collection;
use DomainModel\SpaceModel;
use DomainModel\VenueModel;
use DomainModel\DomainModel;
use Collections\SpaceCollection;
use DomainObjectFactory\DomainObjectFactory;
use DomainObjectFactory\SpaceObjectFactory;
// use Collections\EventCollection;

class SpaceMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    private $selectAllStmt;
    private $findByVenueStmt;

    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = $this->pdo->prepare("SELECT * FROM space WHERE id=?");
        $this->updateStmt = $this->pdo->prepare("UPDATE space SET name=?, venue=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO space ( name, venue ) VALUES ( ?, ? )");
        
        $this->selectAllStmt = $this->pdo->prepare("SELECT * FROM space");

        //Оператор $findByVenueStmt предназначен для выборки объектов типа Space, характерных для отдельного объекта типа Venue.
        $this->findByVenueStmt = $this->pdo->prepare("SELECT * FROM space where venue=?");
    }

    /**
     * Операция добавления новой строки в таблицу space
     * 
     * @param DomainModel\SpaceModel $model
     * 
     * @return void
     */
    protected function doInsert(DomainModel $model)
    {
        //Получение данных из SpaceModel
        $data = [
            $model->getName(),
            $model->getVenue()->getId()
        ];
        $this->insertStmt->execute($data);

        //Здесь происходит магия. Вне зависимости от того, какой был задан id у модели,
        //id будет задан автоматически, и записан в поле id модели
        $id = $this->pdo->lastInsertId();
        $model->setId((int)$id);
    }

    /**
     * Операция обновления строки в таблице space
     * 
     * @param DomainModel\SpaceModel $model
     * 
     * @return void
     */
    protected function doUpdate(DomainModel $model)
    {
        //Получение данных из SpaceModel
        //Запросу надо 2 раза передать Id
        $values = [
            $model->getName(),
            $model->getVenue()->getId(),
            $model->getId(),
            $model->getId()
        ] ;
        $this->updateStmt->execute($values);
    }

    /**
     * Выбрать из таблицы space все строки у которых поле venue = $vid
     * И создать коллекцию с этими данными
     * 
     * По сути, выбирает все подчинённые объекты Space объекту Venue
     * 
     * @return Collections\EventCollection
     */
    public function findByVenue($vid): Collection
    {
        $this->findByVenueStmt->execute([$vid]);

        //Коллекции необходимо передать массив с данными и объект маппер, для создания из данных объектов
        //PDOStatement::fetchAll — Возвращает массив, содержащий все строки результирующего набора 
        return new SpaceCollection( $this->findByVenueStmt->fetchAll(), $this );
    }




    //Далее следуют служебные методы
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
        return SpaceModel::class;
    }

    /**
     * Служебный метод. 
     * Вызывается в конструкторе сперкласса. Возвращает объект фабрики моделей
     * 
     * @return DomainObjectFactory\SpaceObjectFactory
     */
    protected function getFactory(): DomainObjectFactory
    {
        return new SpaceObjectFactory();
    }














    //TODO: Работа с коллекциями
    public function getCollection(array $raw): Collection
    {
        return new SpaceCollection($raw, $this);
    }
}

