<?php
/**
 * 
 */
namespace DomainModel;

class EventModel extends DomainModel
{
    private $space;
    private $start;
    private $duration;
    private $name;

    public function setSpace(int $space)
    {
        $this->space = $space;
        $this->markDirty();
    }

    public function getSpace(): int
    {
        return $this->space;
    }

    public function setStart(string $start)
    {
        $this->start = $start;
        $this->markDirty();
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function setDuration(int $duration)
    {
        $this->duration = $duration;
        $this->markDirty();
    }

    public function getDuration(): int
    {
        return $this->duration;
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
