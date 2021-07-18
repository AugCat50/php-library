<?php 
namespace Assembler;

use PersistanceFactory\PersistanceFactory;

class DomainObjectAssembler
{
    private $factory;

    public function __construct(string $modelName)
    {
        $this->factory = new PersistanceFactory($modelName);

        $this->factory->getModelFactory();
    }

    public function findOne()
    {

    }

    public function find()
    {
        $selFactory = $this->factory->getSelectFactory();
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
