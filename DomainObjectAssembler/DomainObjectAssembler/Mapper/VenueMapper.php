<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект VenueModel
 */
namespace DomainObjectAssembler\Mapper;

use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainModel\VenueModel;
use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\Collections\VenueCollection;
use DomainObjectAssembler\DomainObjectFactory\DomainObjectFactory;
use DomainObjectAssembler\DomainObjectFactory\VenueObjectFactory;

class VenueMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    private $selectAllStmt;

    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = $this->pdo->prepare("SELECT * FROM venue WHERE id=?");
        $this->updateStmt = $this->pdo->prepare("UPDATE venue SET name=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO venue ( name ) VALUES ( ? )");

        $this->selectAllStmt = $this->pdo->prepare("SELECT * FROM venue");
    }

    /**
     * Операция добавления новой строки в таблицу venue
     * 
     * @param DomainModel\VenueModel $model
     * 
     * @return void
     */
    protected function doInsert(DomainModel $model)
    {
        //получение данных из VenueModel
        $values = [
            $model->getName()
        ];
        $this->insertStmt->execute($values);

        //Здесь происходит магия. Вне зависимости от того, какой был задан id у модели,
        //id будет задан автоматически, и записан в поле id модели
        $id = $this->pdo->lastInsertId();
        $model->setId((int)$id);
    }

    /**
     * Операция обновления новой строки в таблице venue
     * 
     * @param DomainModel\VenueModel $model
     * 
     * @return void
     */
    protected function doUpdate(DomainModel $model)
    {
        //получение данных из VenueModel
        //Запросу надо 2 раза передать Id
        $values = [
            $model->getName(),
            $model->getId(),
            $model->getId()
        ] ;
        $this->updateStmt->execute($values);
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
     * Выборку производит метод суперкласса Mapper::find()
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
        return VenueModel::class;
    }

    /**
     * Служебный метод. 
     * Вызывается в конструкторе сперкласса. Возвращает объект фабрики моделей
     * 
     * @return DomainObjectFactory\VenueObjectFactory
     */
    protected function getFactory(): DomainObjectFactory
    {
        return new VenueObjectFactory();
    }





//TODO:  Работа с коллекциями
    public function getCollection(array $raw): Collection
    {
        return new VenueCollection($raw, $this);
    }
}
