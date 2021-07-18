<?php 
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\Mapper\EventMapper;
use DomainObjectAssembler\Mapper\VenueMapper;
use DomainObjectAssembler\DomainModel\SpaceModel;
use DomainObjectAssembler\DomainModel\VenueModel;
use DomainObjectAssembler\DomainModel\DomainModel;

class SpaceObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя обрабатываемой этим маппером модели для проверки
     * Проверка в суперклассе Mapper
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return SpaceModel::class;
    }

    /**
     * Создать объект модели соответствующей мапперу
     * 
     * Создаётся коллекция типа EventCollection 
     * для каждого создаваемого объекта типа SpaceModel.
     * 
     * @return DomainModel\SpaceModel
     */
    protected function doCreateObject(array $array): DomainModel
    {
        if(! ($array['venue'] instanceof VenueModel) ){
            //Если получаем Space обособленно в массиве $raw['venue'] придёт не объект VenueModel, а его id
            $spaceModel  = new SpaceModel( 
                (int)    $array['id'], 
                (string) $array['name'],
                         $array['venue']
            );
        } else {
            $spaceModel  = new SpaceModel( 
                (int)    $array['id'], 
                (string) $array['name'],
                (int)    $array['venue']->getId(),
                         $array['venue']
            );
        }

        //Создаём коллекцию подконтрольных данной Space объектов Event

        //Создать маппер для коллекции EventCollection
        $eventMapper = new EventMapper();

        //Получить данные из БД методом маппера, упаковать в коллекцию, вместе с объектом маппером
        $eventCollection = $eventMapper->findBySpace($array['id']);

        //Записать в модель полученную коллекцию
        $spaceModel->setEvents($eventCollection);

        return $spaceModel;
    }
}