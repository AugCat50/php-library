<?php 
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\TempModel;

class TempObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя обрабатываемой этим маппером модели для проверки
     * Проверка в суперклассе Mapper
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return TempModel::class;
    }

    /**
     * Создать объект модели соответствующей мапперу
     * 
     * Поскольку Ивент находится в самом низу иерархии, коллекцию для него создвать пока не будем
     * 
     * @return DomainModel\EventModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        $model = new TempModel( 
            (int)    $raw['id'],
            (int)    $raw['user_id'],
            (string) $raw['key_act'],
            (string) $raw['mail']
        );

        return $model;
    }
}
