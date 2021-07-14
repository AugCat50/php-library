<?php
/**
 * если встанет вопрос эффективности, то следует полностью реорганизовать исходный код класса SpaceMapper 
 * и получить все необходимые данные за один проход с помощью конструкции JOIN SQL-запроса
 */
namespace Mapper;

use Collections\Collection;
use Collections\SpaceCollection;

class SpaceMapper extends Mapper
{
    private $selectAllStmt;
    private $findByVenueStmt;

    abstract public    function update(DomainModel $object);
    abstract protected function doCreateObject(array $raw): DomainModel;
    abstract protected function doInsert(DomainModel $object);
    abstract protected function selectStmt(): \PDOStatement;
    abstract protected function targetclass(): string;

    public function __construct()
    {
        $this->selectAllStmt   = $this->pdo->prepare("SELECT * FROM space");

        //Здесь внедрен еще один оператор, $findByVenueStmt, предназначенный для 
        //выборки объектов типа Space, характерных для отдельного объекта типа Venue.
        $this->findByVenueStmt = $this->pdo->prepare("SELECT * FROM space where venue=?");
    }

    public function getCollection(array $raw): Collection
    {
        return new SpaceCollection($raw, $this);
    }

    public function findByVenue($vid): Collection
    {
        $this->findByVenueStmt->execute([$vid]);

        return new SpaceCollection( $this->findByVenueStmt->fetchAll(), $this );
    }
}

