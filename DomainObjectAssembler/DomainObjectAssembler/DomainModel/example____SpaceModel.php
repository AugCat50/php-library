<?php
/**
 * Модель для таблицы space
 * 
 * Параметры соответствуют полям тыблицы в БД
 * Методы get set для обслуживания полей
 * 
 * Space содержит в себе Events
 */
namespace DomainObjectAssembler\DomainModel;

use DomainObjectAssembler\Mapper\Mapper;
use DomainObjectAssembler\Registry\Registry;

use DomainObjectAssembler\Mapper\EventMapper;
use DomainObjectAssembler\Mapper\SpaceMapper;
use DomainObjectAssembler\Collections\EventCollection;

class SpaceModel extends DomainModel
{
    private $venue;
    private $venueId;
    private $name;
    private $events;

    /**
     * В объекте SpaceModel надо сохранять объект, который на этот Space ссылается(VenueModel).
     * Это необходимо для шаблона Unit of Work, сначала в БД сохраняется старший объект (VenueModel)
     * и только после этого можно получить его id для сохранения Space. До этого id может не быть.
     */
    public function __construct(int $id, string $name, int $venueId, VenueModel $venue = null)
    {
        $this->name  = $name;
        $this->venue = $venue;

        //При обособленном получении дочерних объектов (Space), может не быть объекта VenueModel
        //Чтобы не делать запрос на объект, который может не пригодиться, просто записываем его id
        $this->venueId = $venueId;

        // $this->events = self::getCollection(EventModel::class) ;
        parent::__construct($id);
    }

    /**
     * Сохранить в свойство старший в иерархии объект
     * Это необходимо для сохранения шаблоном Unit of Work,
     * чтобы получить id объекта VenueModel, после того как он будет сохранён в БД
     * 
     * @param  DomainModel\VenueModel $venue
     * @return void
     */
    public function setVenue(VenueModel $venue)
    {
        $this->venue = $venue;
        $this->markDirty();
    }

    /**
     * Получить старший объект VenueModel
     * 
     * @see    setVenue()
     * @return DomainModel\VenueModel
     */
    public function getVenue(): VenueModel
    {
        return $this->venue;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        $this->markDirty();
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Вместо массива в данном классе применяется объект типа SpaceCollection, чтобы
     * сохранять любые объекты типа Space, которые может содержать объект типа Venue
     * 
     * В данной реализации метода setSpaces () принимается на веру, что все объекты типа Space в коллекции ссылаются на текущий объект типа Venue.
     * В реальном проекте, здесь надо сделать проверку.
     */
    public function setEvents(EventCollection $events)
    {
        $this->events = $events;
    }

    /**
     * В данном методе проверяется, установлено ли свойство $events. Если оно
     * не установлено, то запрашивается средство поиска (т.е. объект типа Mapper)
     * и используется его собственное свойство $id для получения коллекции типа
     * EventCollection, с которой оно связано.
     */
    public function getEvents()
    {
        if (is_null($this->events)) {
            $eventMapper  = new EventMapper();
            $this->events = $eventMapper->findBySpaceId($this->getId());
        }
        return $this->events;
    }

    public function addEvent(EventModel $event)
    {
        $this->getEvents()->add($event);
        $event->setSpace($this);
    }

    /**
     * Получить объект Маппер соответствующий данной модели
     * 
     * @return Mapper\SpaceMapper
     */
    public function getFinder(): Mapper
    {
        return new SpaceMapper();
    }
}
