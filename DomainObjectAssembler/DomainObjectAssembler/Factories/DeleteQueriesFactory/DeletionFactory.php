<?php 
namespace DomainObjectAssembler\Factories\DeleteQueriesFactory;

use DomainObjectAssembler\IdentityObject\IdentityObject;

class DeletionFactory
{
    public function newDeletion(IdentityObject $obj)
    {
        $compArray = $obj->getComps();
        $condition = implode('', $compArray[0]);
        $tableName = $obj->getTableName();
        $queryStr  = "DELETE FROM $tableName WHERE $condition";
        
        return $queryStr;
    }
}
