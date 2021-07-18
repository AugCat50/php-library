<?php
/**
 * В этом классе создается основа оператора SQL, а затем вызывается метод
 * buildWhere (), чтобы ввести в этот оператор условное предложение. На самом
 * деле единственное, что отличает один конкретный класс SelectionFactory
 * от другого в тестовом коде, — это имя таблицы. Если же в ближайшее время
 * не понадобится какая-то особая специализация, эти подклассы можно убрать и
 * пользоваться дальше одним конкретным классом SelectionFactory, где будет
 * запрашиваться имя таблицы из объекта типа PersistenceFactory.
 */
namespace DomainObjectAssembler\Factories\SelectQueriesFactory;

use DomainObjectAssembler\IdentityObject\IdentityObject;

class VenueSelectionFactory extends SelectionFactory
{
    public function newSelection(IdentityObject $obj): array
    {
        $fields = implode(", ", $obj->getObjectFields());
        $core   = "SELECT $fields FROM venue";
        list($where, $values) = $this->buildWhere($obj);
        return [$core . " " . $where, $values];
    }
}
