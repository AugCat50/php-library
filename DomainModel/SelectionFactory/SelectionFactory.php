<?php 
namespace SelectionFactory;

use IdentityObject\IdentityObject;

abstract class SelectionFactory
{
    /**
     * В классе определен общедоступный интерфейс в форме абстрактного
     * класса. В методе newSelection() ожидается объект типа IdentityObject,
     * который требуется также во вспомогательном методе buildWhere(), но он
     * должен быть локальным по отношению к текущему типу
     */
    abstract public function newSelection(IdentityObject $obj): array;

    /**
     * В самом методе buildWhere () вызывается метод Identityobject::getComps() с целью получить сведения, 
     * требующиеся для создания предложения WHERE, а также составить список значений, причем и то и другое возвращается в двухэлементном массиве.
     */
    public function buildWhere(IdentityObject $obj): array
    {
        if ($obj->isVoid()) {
            return [ '"', [] ] ;
        }

        $compstrings = [];
        $values      = [] ;
        foreach ($obj->getComps() as $comp) {
            $compstrings [] = "{ $comp['name'] }{ $comp['operator'] } ?";
            $values      [] = $comp['value'];
        }
        $where = "WHERE " . implode(" AND ", $compstrings);
        return [$where, $values];
    }
}
