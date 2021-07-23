<?php
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserThemeModel;

class UserThemeUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * 
     * @param DomainObjectAssembler\DomainModel\UserThemeModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof UserThemeModel){
            throw new \Exception('UserThemeUpdateFactory(21): Oбъект должен быть типа: '. UserThemeModel::class . ' ---- Получен: '. get_class($obj));
        }
        
        $id                = $obj->getId();
        $values['user_id'] = $obj->getUserId();
        $values['name']    = $obj->getName();

        $cond = null;

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("user_themes", $values, $cond);
    }
}
