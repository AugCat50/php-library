<?php
namespace DomainObjectAssembler\Factories\UpdateFactory;

use DomainObjectAssembler\DomainModel\DomainModel;

abstract class UpdateFactory
{
    /**
     * С точки зрения интерфейса в этом классе определяется лишь метод 
     * newUpdate (), возвращающий массив, содержащий строку запроса, а также
     * список условий, которые требуется применить в ней.
     * 
     * Обратите внимание на то, что методу newUpdate() можно передать любой 
     * объект типа Domainobject. Дело в том, что все классы типа UpdateFactory
     * могут совместно использовать общий интерфейс. К этому было бы неплохо добавить дополнительную проверку типов, 
     * чтобы гарантировать, что неверный объект не будет передан.
     */
    abstract public function newUpdate(DomainModel $obj): array;

    /**
     * Метод buildStatement() выполняет общую работу по составлению запроса на обновление, а всю работу, 
     * а не конкретные действия над отдельными объектами предметной области, выполняют дочерние классы. 
     * Методу buildStatement() передаются имя таблицы, ассоциативный массив полей и их значений и аналогичный ассоциативный 
     * массив условий. Все это объединяется в данном методе для составления запроса.
     * 
     * Имя таблицы (UPDATE venue)
     * @param string $table
     * 
     * Массив полей, которые будут затронуты в запросе (SET name = ?)
     * @param array $fields
     * 
     * Массив условий (WHERE id = 1)
     * @param array $conditions
     */
    protected function buildStatement(string $table, array $fields, array $conditions = null): array
    {
        $terms = array();

        if (! is_null($conditions)) {
            $query  = "UPDATE {$table} SET ";
            $query .= implode (" = ?,", array_keys($fields)) . " = ?";
            $terms  = array_values($fields);
            $cond   = [];
            $query .= " WHERE ";

            foreach ($conditions as $key => $val) {
                $cond [] = "$key = ?";
                $terms[] = $val;
            }

            $query .= implode(" AND ", $cond);
        } else {
            $query  = "INSERT INTO {$table} (";
            $query .= implode(",", array_keys($fields));
            $query .= ") VALUES (";

            foreach ($fields as $name => $value) {
                $terms[] = $value;
                $qs   [] = '?';
            }

            $query .= implode(",", $qs);
            $query .= ")";
        }

        return array($query, $terms);
    }
}
