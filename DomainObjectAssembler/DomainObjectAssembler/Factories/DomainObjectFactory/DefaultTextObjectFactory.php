<?php 
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\DefaultTextModel;

class DefaultTextObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя обрабатываемой этим маппером модели для проверки
     * Проверка в суперклассе Mapper
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return DefaultTextModel::class;
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
        $model = new DefaultTextModel( 
            (int)    $raw['id'],
            (string) $raw['name'],
            (string) $raw['text'],
            (bool)   $raw['hidden']
        );

        return $model;
    }
}
