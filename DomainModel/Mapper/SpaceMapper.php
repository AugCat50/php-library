<?php
/**
 * если встанет вопрос эффективности, то следует полностью реорганизовать исходный код класса SpaceMapper 
 * и получить все необходимые данные за один проход с помощью конструкции JOIN SQL-запроса
 */
namespace Mapper;

use Collections\Collection;
use DomainModel\SpaceModel;
use DomainModel\VenueModel;
use DomainModel\DomainModel;
use Collections\SpaceCollection;

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


        $this->selectAllStmt   = $this->pdo->prepare("SELECT * FROM space");

        //Здесь внедрен еще один оператор, $findByVenueStmt, предназначенный для 
        //выборки объектов типа Space, характерных для отдельного объекта типа Venue.
        $this->findByVenueStmt = $this->pdo->prepare("SELECT * FROM space where venue=?");
    }

    /**
     * Создать объект модели соответствующей мапперу
     * 
     * Создаётся коллекция типа EventCollection 
     * для каждого создаваемого объекта типа SpaceModel.
     * 
     * @return DomainModel\SpaceModel
     */
    protected function doCreateObject(array $array): DomainModel
    {
        $spaceModel  = new SpaceModel( 
                            (int)   $array['id'], 
                            (string)$array['name'], 
                            (int)   $array['venue'] 
                        );
        // $spaceMapper = new SpaceMapper();
        // $spaceCollection = $spaceMapper->findByVenue($array['id']);
        // $spaceModel->setSpaces($spaceCollection);
        return $spaceModel;
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
     * Операция добавления новой строки в таблицу space
     * 
     * @param SpaceModel $model
     * 
     * @return void
     */
    protected function doInsert(DomainModel $model)
    {
        //Получение данных из SpaceModel
        $data = [
            $model->getName(),
            $model->getVenue()
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
     * @param SpaceModel $model
     * 
     * @return void
     */
    public function update(DomainModel $model)
    {
        //Запросу надо 2 раза передать Id
        $values = [
            $model->getName(),
            $model->getVenue(),
            $model->getId(),
            $model->getId()
        ] ;
        $this->updateStmt->execute($values);
    }





//TODO: Работа с коллекциями


    public function findByVenue($vid): Collection
    {
        $this->findByVenueStmt->execute([$vid]);

        return new SpaceCollection( $this->findByVenueStmt->fetchAll(), $this );
    }







    /**
     * Вернуть имя соответствующей мапперу модели
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return SpaceModel::class;
    }


    public function getCollection(array $raw): Collection
    {
        return new SpaceCollection($raw, $this);
    }


}

