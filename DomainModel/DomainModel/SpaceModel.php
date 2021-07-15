<?php
/**
 * Модель для таблицы space
 * 
 * Параметры соответствуют полям тыблицы в БД
 * Методы get set для обслуживания полей
 * 
 * Space содержит в себе Events
 */
namespace DomainModel;

use Collections\EventCollection;
use Registry\Registry;

class SpaceModel extends DomainModel
{
    private $venue;
    private $name;
    private $events;

    public function __construct(int $id, string $name, int $venue)
    {
        $this->name  = $name;
        $this->venue = $venue;
        // $this->events = self::getCollection(EventModel::class) ;
        parent::__construct($id);
    }

    public function setVenue(int $venue)
    {
        $this->venue = $venue;
        $this->markDirty();
    }

    public function getVenue(): int
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

    public function getEvents() : EventCollection
    {
        if (is_null($this->events)) {
            $reg = Registry::getInstance();
            $this->events = $reg->getEventCollection();
        }
        return $this->events;
    }

    public function addEvent(EventModel $event)
    {
        $this->getEvents()->add($event);
        // $event->setSpace($this);
        $event->setSpace($this->getId());
    }
}
