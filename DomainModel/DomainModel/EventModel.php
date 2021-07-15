<?php
/**
 * Модель для таблицы some_event
 * 
 * Параметры соответствуют полям тыблицы в БД
 * Методы get set для обслуживания полей
 */
namespace DomainModel;

use Mapper\Mapper;
use Mapper\EventMapper;

class EventModel extends DomainModel
{
    private $space;
    private $start;
    private $duration;
    private $name;

    // public function __construct(int $id, int $space, string $start, int $duration, string $name)
    // {
    //     $this->space    = $space;
    //     $this->start    = $start;
    //     $this->duration = $duration;
    //     $this->name     = $name;
    //     // $this->events = self::getCollection(EventModel::class) ;
    //     parent::__construct($id);
    // }

    // public function setSpace(int $space)
    // {
    //     $this->space = $space;
    //     $this->markDirty();
    // }

    // public function getSpace(): int
    // {
    //     return $this->space;
    // }

    /**
     * В объекте EventModel надо сохранять объект, который на этот ивент ссылается (SpaceModel).
     * Это необходимо для шаблона Unit of Work, сначала в БД сохраняется старший объект (SpaceModel)
     * и только после этого можно получить его id для сохранения ивентов. До этого id может не быть.
     */
    public function __construct(int $id, string $start, int $duration, string $name, SpaceModel $space = null)
    {
        $this->space    = $space;
        $this->start    = $start;
        $this->duration = $duration;
        $this->name     = $name;
        // $this->events = self::getCollection(EventModel::class) ;
        parent::__construct($id);
    }

    public function setSpace(SpaceModel $space)
    {
        $this->space = $space;
        $this->markDirty();
    }

    public function getSpace(): SpaceModel
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

    public function getFinder(): Mapper
    {
        return new EventMapper();
    }
}
