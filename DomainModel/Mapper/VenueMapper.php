<?php
/**
 * В дочерних классах будут также реализованы специальные методы обнаружения данных по заданным критериям 
 * (нам, например, нужно будет найти объекты типа Space, относящиеся к объектам типа Venue). 
 * Этот процесс можно рассматривать и с точки зрения дочернего класса, как показано ниже.
 */
namespace Mapper;

use Collections\Collection;
use DomainModel\VenueModel;
use DomainModel\DomainModel;
use Collections\VenueCollection;

class VenueMapper extends Mapper
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
        $this->selectStmt = $this->pdo->prepare("SELECT * FROM venue WHERE id=?");
        $this->updateStmt = $this->pdo->prepare("UPDATE venue SET name=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO venue ( name ) VALUES ( ? )");

        $this->selectAllStmt = $this->pdo->prepare("SELECT * FROM venue");
    }

    /**
     * Создать объект модели соответствующей мапперу
     * 
     * Создаётся коллекция типа SpaceCollection 
     * для каждого создаваемого объекта типа VenueModel.
     * 
     * @return DomainModel\VenueModel
     */
    protected function doCreateObject(array $array): DomainModel
    {
        $venueModel = new VenueModel( 
                            (int)   $array['id'], 
                            (string)$array['name'] 
                        );
        // $spaceMapper     = new SpaceMapper();
        // $spaceCollection = $spaceMapper->findByVenue($array['id']);
        // $obj->setSpaces($spaceCollection);
        return $venueModel;
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
    public function update(DomainModel $model)
    {
        //Запросу надо 2 раза передать Id
        $values = [
            $model->getName(),
            $model->getId(),
            $model->getId()
        ] ;
        $this->updateStmt->execute($values);
    }




//TODO:  Работа с коллекциями

    /**
     * Вернуть имя соответствующей мапперу модели
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return VenueModel::class;
    }

    public function getCollection(array $raw): Collection
    {
        return new VenueCollection($raw, $this);
    }
}
