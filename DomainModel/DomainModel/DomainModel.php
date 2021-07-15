<?php 
namespace DomainModel;

use Collections\Collection;

abstract class DomainModel
{
    private $id;

    public function __construct(int $id)
    {
        //В случае создания модели для несуществующей строки в БД,
        //Заданный id игнорируется маппером и создаётся автоматически при выполнении doInsert()
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

    // public static function getCollection(string $type): Collection
    public static function getCollection(string $type)
    {
        // фиктивная реализация
        return Collection::getCollection($type);
    }

    public function markDirty()
    {
        // реализация этого метода приведена в следующей главе!
    }
}
