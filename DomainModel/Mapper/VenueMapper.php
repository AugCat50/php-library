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

    /**
     * Заранее подготавливаем запросы к БД
     */
    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = $this->pdo->prepare("SELECT * FROM venue WHERE id=?");
        $this->updateStmt = $this->pdo->prepare("UPDATE venue SET name=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO venue ( name ) VALUES ( ? )");
    }

    protected function targetClass(): string
    {
        return VenueModel::class;
    }

    public function getCollection(array $raw): Collection
    {
        return new VenueCollection($raw, $this);
    }

    // protected function doCreateObject(array $raw): DomainModel
    // {
    //     $obj = new VenueModel( (int)$raw['id'], $raw['name'] );
    //     return $obj;
    // }

    /**
     * Создаётся коллекция типа SpaceCollection 
     * для каждого создаваемого объекта типа VenueModel.
     */
    protected function doCreateObject(array $array): DomainModel
    {
        $obj             = new VenueModel( (int)$array['id'], $array['name'] );
        $spaceMapper     = new SpaceMapper();
        $spaceCollection = $spaceMapper->findByVenue($array['id']);
        $obj->setSpaces($spaceCollection);
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

    protected function selectAllStmt(): \PDOStatement;
    {

    }

    protected function getCollection(array $raw): Collection;
    {
        
    }
}
