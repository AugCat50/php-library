<?php
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\DefaultTextModel;

class DefaultTextUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * 
     * @param DomainObjectAssembler\DomainModel\DefaultTextModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof DefaultTextModel){
            throw new \Exception('DefaultTextUpdateFactory(21): Oбъект должен быть типа: '. DefaultTextModel::class . ' ---- Получен: '. get_class($obj));
        }
        
        $id               = $obj->getId();
        $values['name']   = $obj->getName();
        $values['text']   = $obj->getText();
        $values['hidden'] = $obj->getHidden();

        $cond = null;

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("Default_texts", $values, $cond);
    }
}
