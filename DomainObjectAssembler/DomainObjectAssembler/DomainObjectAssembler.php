<?php 
namespace DomainObjectAssembler;

use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\IdentityObject\IdentityObject;

class DomainObjectAssembler
{
    private $factory;

    public function __construct(string $modelName)
    {
        $this->factory = new PersistanceFactory($modelName);
        // $this->factory->getModelFactory();
    }

    /**
     * Делегируем PersistanceFactory получение объекта идентичности
     */
    public function getIdentityObject(): IdentityObject
    {
        $identityObject = $this->factory->getIdentityObject();
        return $identityObject;
    }

    // public function findOne()
    // {

    // }

    // public function find()
    // {
    //     $selFactory = $this->factory->getSelectFactory();
    // }

    
    public function findOne(IdentityObject $idobj): DomainModel
    {
        $collection = $this->find($idobj);

        return $collection->next();
    }

    public function find(IdentityObject $idobj): Collection
    {
        //Получить объект SelectionFactory
        $selfact = $this->factory->getSelectionFactory();

        d($selfact,1);

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

    public function insert()
    {
        $insFactory = $this->factory->getInsertFactory();
    }

    public function update()
    {
        $updFactory = $this->factory->getUpdateFactory();
    }

    public function delete()
    {
        $delFactory = $this->factory->getDeleteFactory();
    }
}
