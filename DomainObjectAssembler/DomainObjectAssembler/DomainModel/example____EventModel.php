<?php
/**
 * Модель для таблицы some_event
 * 
 * Параметры соответствуют полям тыблицы в БД
 * Методы get set для обслуживания полей
 */
namespace DomainObjectAssembler\DomainModel;

use DomainObjectAssembler\Mapper\Mapper;
use DomainObjectAssembler\Mapper\EventMapper;

class EventModel extends DomainModel
{
    private $space;
    private $spaceId;
    private $start;
    private $duration;
    private $name;

    /**
     * В объекте EventModel надо сохранять объект, который на этот ивент ссылается (SpaceModel).
     * Это необходимо для шаблона Unit of Work, сначала в БД сохраняется старший объект (SpaceModel)
     * и только после этого можно получить его id для сохранения ивентов. До этого id может не быть.
     */
    public function __construct(int $id, string $start, int $duration, string $name, int $spaceId, SpaceModel $space = null)
    {
        $this->space    = $space;
        $this->start    = $start;
        $this->duration = $duration;
        $this->name     = $name;

        //При обособленном получении дочерних объектов (Event), может не быть объекта SpaceModel
        //Чтобы не делать запрос на объект, который может не пригодиться, просто записываем его id
        $this->spaceId  = $spaceId;

        // $this->events = self::getCollection(EventModel::class) ;
        parent::__construct($id);
    }

    /**
     * Сохранить в свойство старший в иерархии объект
     * Это необходимо для сохранения шаблоном Unit of Work,
     * чтобы получить id объекта SpaceModel, после того как он будет сохранён в БД
     * 
     * @param  DomainModel\SpaceModel $space
     * @return void
     */
    public function setSpace(SpaceModel $space)
    {
        $this->space = $space;
        $this->markDirty();
    }

    /**
     * Получить старший объект SpaceModel
     * 
     * @see    setSpace()
     * @return DomainModel\SpaceModel
     */
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

    /**
     * Служебный метод.
     * Получить объект Маппер соответствующий данной модели
     * 
     * @return Mapper\EventMapper
     */
    public function getFinder(): Mapper
    {
        return new EventMapper();
    }
}
