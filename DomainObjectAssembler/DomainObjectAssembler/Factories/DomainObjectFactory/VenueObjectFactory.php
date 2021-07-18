<?php
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\Mapper\SpaceMapper;
use DomainObjectAssembler\DomainModel\VenueModel;
use DomainObjectAssembler\DomainModel\DomainModel;

class VenueObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя обрабатываемой этой фабрикой модели для проверки наличия объекта в ObjectWatcher
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return VenueModel::class;
    }

    /**
     * Создать объект модели из сырых данных (массива)
     * 
     * Создаётся коллекция типа SpaceCollection 
     * для каждого создаваемого объекта типа VenueModel.
     * 
     * @return DomainModel\VenueModel
     */
    protected function doCreateObject(array $array): DomainModel
    {
        $venueModel = new VenueModel( 
                            (int)   $array['id'], 
                            (string)$array['name'] 
                        );

        //Создаём коллекцию подконтрольных данной Venue объектов Space

        //Создать маппер для коллекции SpaceCollection
        $spaceMapper = new SpaceMapper();

        //Получить данные из БД методом маппера, упаковать в коллекцию, вместе с объектом маппером
        $spaceCollection = $spaceMapper->findByVenue($array['id']);

        //Записать в модель полученную коллекцию
        $venueModel->setSpaces($spaceCollection);

        return $venueModel;
    }
}
