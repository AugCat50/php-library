<?php 
namespace DomainModel;

use Collections\Collection;

abstract class DomainModel
{
    private $id;

    /**
     * Конструктор
     * 
     * В случае создания модели для несуществующей строки в БД,
     * Заданный id игнорируется маппером и создаётся автоматически при выполнении doInsert()
     * 
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    //TODO: пока не очевидно, надо ли получать пустую коллекцию. Пусть пока будет
    // public static function getCollection(string $type): Collection
    public static function getCollection(string $type)
    {
        // фиктивная реализация
        return Collection::getCollection($type);
    }

    public function markDirty()
    {
        // Для шаблона Unit of Work
    }
}
