<?php
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserModel;

class UserUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * 
     * @param DomainObjectAssembler\DomainModel\UserModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof UserModel){
            throw new \Exception('UserUpdateFactory(21): Oбъект должен быть типа: '. UserModel::class . ' ---- Получен: '. get_class($obj));
        }
        
        $id                 = $obj->getId();
        $values['name']     = $obj->getName();
        $values['password'] = $obj->getPassword();
        $values['solt']     = $obj->getSolt();
        $values['mail']     = $obj->getMail();

        $cond = null;

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("users", $values, $cond);
    }
}
