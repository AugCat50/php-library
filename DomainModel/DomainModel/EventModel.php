<?php
/**
 * Модель для таблицы some_event
 * 
 * Параметры соответствуют полям тыблицы в БД
 * Методы get set для обслуживания полей
 */
namespace DomainModel;

class EventModel extends DomainModel
{
    private $space;
    private $start;
    private $duration;
    private $name;

    public function __construct(int $id, int $space, string $start, int $duration, string $name)
    {
        $this->space    = $space;
        $this->start    = $start;
        $this->duration = $duration;
        $this->name     = $name;
        // $this->events = self::getCollection(EventModel::class) ;
        parent::__construct($id);
    }

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
