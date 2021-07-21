<?php
/**
 * В данной реализации происходит непосредственное оперирование объектом 
 * типа Domainobject. В тех системах, где одновременно может обновляться много объектов, 
 * можно воспользоваться объектом идентичности для определения совокупности объектов, которыми требуется оперировать. 
 */
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserTextModel;

class UserTextUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate () извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * 
     * @param DomainObjectAssembler\DomainModel\UserTextModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof UserTextModel){
            throw new \Exception('UserTextUpdateFactory(21): Oбъект должен быть типа: '. UserTextModel::class . ' ---- Получен: '. get_class($obj));
        }
        
        $id                    = $obj->getId();
        $values['user_id']     = $obj->getUserId();
        $values['user_themes'] = $obj->getUserThemes();
        $values['name']        = $obj->getName();
        $values['text']        = $obj->getText();

        $cond = null;

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("user_texts", $values, $cond);
    }
}
