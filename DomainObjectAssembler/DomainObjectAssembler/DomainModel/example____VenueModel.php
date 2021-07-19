<?php
 /**
 * Модель для таблицы venue
 * 
 * Параметры соответствуют полям тыблицы в БД
 * Методы get set для обслуживания полей
 * 
 * Venue содержит в себе Spaces
 */
namespace DomainObjectAssembler\DomainModel;

use DomainObjectAssembler\Registry\Registry;
use DomainObjectAssembler\Collections\SpaceCollection;

use DomainObjectAssembler\Mapper\Mapper;
use DomainObjectAssembler\Mapper\VenueMapper;

class VenueModel extends DomainModel
{
    private $name;
    private $spaces;

    public function __construct(int $id, string $name)
    {
        $this->name = $name;
        // $this->spaces = self::getCollection(SpaceModel::class) ;
        parent::__construct($id);
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
    public function setSpaces(SpaceCollection $spaces)
    {
        $this->spaces = $spaces;
    }

    public function getSpaces(): SpaceCollection
    {
        if (is_null($this->spaces)) {
            $reg = Registry::getInstance();
            $this->spaces = $reg->getSpaceCollection();
        }
        return $this->spaces;
    }

    public function addSpace(SpaceModel $space)
    {
        $this->getSpaces()->add($space);
        $space->setVenue($this);
    }

    /**
     * Получить объект Маппер соответствующий данной модели
     * 
     * @return Mapper\VenueMapper
     */
    public function getFinder(): Mapper
    {
        return new VenueMapper();
    }
}
