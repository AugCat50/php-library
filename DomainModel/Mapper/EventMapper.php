<?php 
namespace Mapper;

class EventMapper extends Mapper
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
        $this->updateStmt = $this->pdo->prepare("UPDATE some_event SET name=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO some_event ( name ) VALUES ( ? )");
    }

    abstract public    function update(DomainModel $object);
    abstract protected function doCreateObject(array $raw): DomainModel;
    abstract protected function doInsert(DomainModel $object);
    abstract protected function selectStmt(): \PDOStatement;
    abstract protected function targetclass(): string;
}
