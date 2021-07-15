<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект VenueModel
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

        //Создаём коллекцию подконтрольных данной Venue объектов Space

        //Создать маппер для коллекции SpaceCollection
        $spaceMapper = new SpaceMapper();

        //Получить данные из БД методом маппера, упаковать в коллекцию, вместе с объектом маппером
        $spaceCollection = $spaceMapper->findByVenue($array['id']);

        //Записать в модель полученную коллекцию
        $venueModel->setSpaces($spaceCollection);

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

    /**
     * Получить имя обрабатываемой этим маппером модели для проверки
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return VenueModel::class;
    }


//TODO:  Работа с коллекциями



    public function getCollection(array $raw): Collection
    {
        return new VenueCollection($raw, $this);
    }
}
