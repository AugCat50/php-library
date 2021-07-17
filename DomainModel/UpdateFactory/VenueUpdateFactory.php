<?php
/**
 * В данной реализации происходит непосредственное оперирование объектом 
 * типа Domainobject. В тех системах, где одновременно может обновляться много объектов, 
 * можно воспользоваться объектом идентичности для определения совокупности объектов, которыми требуется оперировать. 
 */
namespace UpdateFactory;

use DomainModel\DomainModel;

class VenueUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate () извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     */
    public function newUpdate(DomainModel $obj): array
    {
        // Обратите внимание на то, что здесь была
        // исключена проверка типов
        $id             = $obj->getId();
        $cond           = null;
        $values['name'] = $obj->getName();

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("venue", $values, $cond);
    }
}
