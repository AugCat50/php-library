<?php
/**
 * Фабрика подготовки SQL оператора SELECT
 * ["SELECT id, name FROM default_text WHERE hidden = ?", $values]
 */
namespace DomainObjectAssembler\Factories\SelectQueriesFactory;

use DomainObjectAssembler\IdentityObject\IdentityObject;

// abstract class SelectionFactory
class SelectionFactory
{
    /**
     * На случай, если SELECT будут как-то сильно отличаться: 
     * 
     * В классе определен общедоступный интерфейс в форме абстрактного
     * класса. В методе newSelection() ожидается объект типа IdentityObject,
     * который требуется также во вспомогательном методе buildWhere(), но он
     * должен быть локальным по отношению к текущему типу
     */
    // abstract public function newSelection(IdentityObject $obj): array;

    /**
     * В этом классе создается основа оператора SQL, а затем вызывается метод
     * buildWhere(), чтобы ввести в этот оператор условное предложение.
     * 
     * @param DomainObjectAssembler\IdentityObject\IdentityObject $obj
     * 
     * @return array
     */
    public function newSelection(IdentityObject $obj): array
    {
        //Обращаемся к IdentityObject\Field чтобы узнать какие поля надо и можно получить
        $fields    = implode(", ", $obj->getObjectFields());
        $tableName = $obj->getTableName();
        $core      = "SELECT $fields FROM $tableName";

        list($where, $values) = $this->buildWhere($obj);

        //Получается массив готовый для prepare, типа:
        //[0] => "SELECT id, name, text, hidden FROM default_texts WHERE name = ? AND id = ?"
        //[1] => [0] => 'имя', [1] => int(4)
        return [$core . " " . $where, $values];
    }

    /**
     * В самом методе buildWhere() вызывается метод IdentityObject::getComps() с целью получить сведения, 
     * требующиеся для создания предложения WHERE, а также составить список значений, причем и то и другое возвращается в двухэлементном массиве.
     * 
     * @param DomainObjectAssembler\IdentityObject\IdentityObject $obj
     * 
     * @return array
     */
    public function buildWhere(IdentityObject $obj): array
    {
        if ($obj->isVoid()) {
            return [ '"', [] ] ;
        }

        $compstrings = [];
        $values      = [] ;

        foreach ($obj->getComps() as $comp) {
            // name operator value
            $compstrings[] = $comp['name']. $comp['operator']. '?';
            $values     [] = $comp['value'];
        }
        $where = "WHERE " . implode(" AND ", $compstrings);
        return [$where, $values];
    }
}
