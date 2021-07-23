<?php
namespace DomainObjectAssembler\DomainModel;

class UserThemeModel extends DomainModel
{
    private $user_id;
    private $name;

    public function __construct(int $id, int $user_id, string $name)
    {
        $this->user_id = $user_id;
        $this->name    = $name;
        parent::__construct($id);
    }

    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
        $this->markDirty();
    }

    public function getUserId(): int
    {
        return $this->user_id;
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

    public function getModelName(): string
    {
        return 'UserTheme';
    }
}