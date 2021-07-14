<?php
/**
 * Space содержит в себе Events
 */
namespace DomainModel;

class SpaceModel extends DomainModel
{
    private $venue;
    private $name;

    public function setVenue(VenueModel $venue)
    {
        $this->venue = $venue;
        $this->markDirty();
    }

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
}
