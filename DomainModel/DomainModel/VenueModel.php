<?php 
namespace DomainModel;

class VenueModel extends DomainModel
{
    private $name;
    private $spaces;

    public function __construct (int $id, string $name)
    {
        $this->name = $name;
        $this->spaces = self::getCollection(Space::class) ;
        parent::__construct($id);
    }

    /**
     * Вместо массива в данном классе применяется объект типа SpaceCollection, чтобы
     * сохранять любые объекты типа Space, которые может содержать объект типа Venue
     */
    public function setSpaces(SpaceCollection $spaces)
    {
        $this->spaces = $spaces;
    }

    public function getSpaces(): SpaceCollection
    {
        return $this->spaces;
    }

    public function addSpace(Space $space)
    {
        $this->spaces->add($space);
        $space->setVenue($this);
    }

    public function setName(string $name)
    {
        $this->name = $name;
        $this->markDirty ();
    }

    public function getNameO(): string
    {
        return $this->name;
    }
}
