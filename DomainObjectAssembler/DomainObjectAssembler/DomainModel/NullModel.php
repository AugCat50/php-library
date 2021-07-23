<?php
namespace DomainObjectAssembler\DomainModel;

class NullModel extends DomainModel
{
    public function __construct()
    {
        parent::__construct(0);
    }

    public function __call($name, $arguments){
        return null;
    }

    public function __get($name){
        return null;
    }

    public function __set($name, $value){
        return null;
    }

    public function getModelName(): string
    {
        return 'Null';
    }
}
