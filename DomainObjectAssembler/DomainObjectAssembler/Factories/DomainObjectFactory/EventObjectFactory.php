<?php 

namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\Mapper\SpaceMapper;
use DomainObjectAssembler\DomainModel\EventModel;
use DomainObjectAssembler\DomainModel\SpaceModel;
use DomainObjectAssembler\DomainModel\DomainModel;

class EventObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя обрабатываемой этим маппером модели для проверки
     * Проверка в суперклассе Mapper
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return EventModel::class;
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
        if(! ($raw['space'] instanceof SpaceModel) ){

            //Если получаем Event обособленно в массиве $raw['space'] придёт не объект SpaceModel, а его id
            $eventModel = new EventModel( 
                (int)    $raw['id'],
                (string) $raw['start'],
                (int)    $raw['duration'],
                (string) $raw['name'],
                (int)    $raw['space']
            );

        } else {

            $eventModel = new EventModel( 
                (int)    $raw['id'],
                (string) $raw['start'],
                (int)    $raw['duration'],
                (string) $raw['name'],
                (int)    $raw['space']->getId(),
                         $raw['space']
            );

        }

        return $eventModel;
    }
}

