<?php 
namespace DomainModel;

abstract class DomainModel
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getld(): int
    {
        return $this->id;
    }

    public static function getCollection(string $type): Collection
    {
        // фиктивная реализация
        return Collection::getCollection($type);
    }

    public function markDirty()
    {
        // реализация этого метода приведена в следующей главе!
    }
}
