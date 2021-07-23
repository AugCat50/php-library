<?php
/**
 * Суперкласс моделей
 * Реализует методы get set для работы с ID,
 * так же реализует делегирующие методы шаблона Unit of Work класса ObjectWatcher,
 * для постановки задач на работу с БД
 */
namespace DomainObjectAssembler\DomainModel;

use DomainObjectAssembler\Mapper\Mapper;
use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;
use PDO;

abstract class DomainModel
{
    private $id;

    // abstract public function getFinder(): Mapper;
    abstract public function getModelName(): string;

    /**
     * Конструктор
     * 
     * В случае создания модели для несуществующей строки в БД,
     * Заданный id игнорируется маппером и создаётся автоматически при выполнении doInsert()
     * 
     * @param int $id
     */
    public function __construct(int $id = -1)
    {
        $this->id = $id;

        //Объект модели регистрируется в ObjectWatcher, если не передан id > 0 - как новый
        //WARNING:: новые объекты могут автоматически попадать на сохранение в базу данных
        if ($id < 0) {
            $this->markNew();
        } else {
            $this->addToMap();
        }
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAssembler()
    {
        $modelName = $this->getModelName();
        return new DomainObjectAssembler($modelName);
    }


    //Unit of Work methods
    public function markNew()
    {
        ObjectWatcher::addNew($this);
    }

    public function markDeleted()
    {
        ObjectWatcher::addDelete($this);
    }

    public function markDirty()
    {
        ObjectWatcher::addDirty($this);
    }

    public function markClean()
    {
        ObjectWatcher::addClean($this) ;
    }

    public function addToMap()
    {
        ObjectWatcher::add($this);
    }




    //TODO: пока не очевидно, надо ли получать пустую коллекцию. Пусть пока будет
    // public static function getCollection(string $type): Collection
    public static function getCollection(string $type)
    {
        // фиктивная реализация
        return Collection::getCollection($type);
    }
}
