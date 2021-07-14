<?php 
namespace DomainModel;

class SpaceModel extends DomainModel
{
    public $venue;

    public function setVenue(VenueModel $venue)
    {
        $this->venue = $venue;
    }
}
