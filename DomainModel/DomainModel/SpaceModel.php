<?php
/**
 * Space содержит в себе Events
 */
namespace DomainModel;

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

    // public function setVenue(VenueModel $venue)
    // {
    //     $this->venue = $venue;
    //     $this->markDirty();
    // }

    // public function getVenue(): VenueModel
    // {
    //     return $this->venue;
    // }

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

    // public function getEvents() : SpaceCollection
    // {
    //     if (is_null($this->spaces)) {
    //         $reg = Registry::getInstance();
    //         $this->spaces = $reg->getSpaceCollection();
    //     }
    //     return $this->spaces;
    // }

    // /**
    //  * Вместо массива в данном классе применяется объект типа SpaceCollection, чтобы
    //  * сохранять любые объекты типа Space, которые может содержать объект типа Venue
    //  * 
    //  * В данной реализации метода setSpaces () принимается на веру, что все объекты типа Space в коллекции ссылаются на текущий объект типа Venue.
    //  * В реальном проекте, здесь надо сделать проверку.
    //  */
    // public function setEvents(SpaceCollection $spaces)
    // {
    //     $this->spaces = $spaces;
    // }

    // public function addEvent(EventModel $event)
    // {
    //     $this->getEvents()->add($event);
    //     $event->setSpace($this);
    // }
}
