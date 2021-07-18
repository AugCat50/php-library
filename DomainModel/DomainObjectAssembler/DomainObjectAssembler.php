<?php
/**
 * Контроллер, действующий на уровне представления данных
 */
namespace DomainObjectAssembler;

use Registry\Registry;
use Collections\Collection;
use DomainModel\DomainModel;
use IdentityObject\IdentityObject;

class DomainObjectAssembler
{
    protected $pdo = null;

    /**
     * Вместо его разделения по разным специализациям в нем применяется класс PersistenceFactory, 
     * чтобы гарантировать получение подходящих компонентов для текущего объекта предметной области.
     */
    public function __construct(PersistenceFactory $factory)
    {
        $reg           = Registry::getInstance ();
        $this->pdo     = $reg->getPdo();
        $this->factory = $factory;
    }

    public function getStatement(string $str): \PDOStatement
    {
        if (! isset($this->statements[$str])) {
            $this->statements[$str] = $this->pdo->prepare($str);
        }
        return $this->statements[$str];
    }

    public function findOne(IdentityObject $idobj): DomainModel
    {
        $collection = $this->find($idobj);

        return $collection->next();
    }

    public function find(IdentityObject $idobj): Collection
    {
        //Получить объект SelectionFactory
        $selfact                   = $this->factory->getSelectionFactory();

        //Полоучить лист запрос - переменные в объекте SelectionFactory
        list ($selection, $values) = $selfact->newSelection($idobj);

        //подготовить запрос prepare
        $stmt = $this->getStatement($selection);

        //Выполнить запрос
        $stmt->execute($values);

        //Извлечь массив данных
        $raw = $stmt->fetchAll();
        return $this->factory->getCollection($raw);
    }

    public function insert(DomainModel $obj)
    {
    $upfact = $this->factory->getUpdateFactory();
    list($update, $values) = $upfact->newUpdate($obj);
    $stmt = $this->getStatement($update);
    $stmt->execute($values) ;
    if ($obj->getId() < 0) {
        $obj->setId((int)$this->pdo->lastInsertId());
    }
    $obj->markClean();
    }
}
