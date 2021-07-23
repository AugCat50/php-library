<?php
namespace DomainObjectAssembler\DomainModel;

class UserTextModel extends DomainModel
{
    private $user_id;
    private $user_themes;
    private $text;

    public function __construct(int $id, int $user_id, string $user_themes, string $name, string $text)
    {
        $this->user_id     = $user_id;
        $this->user_themes = $user_themes;
        $this->name        = $name;
        $this->text        = $text;
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

    public function setUserThemes(string $user_themes)
    {
        $this->user_themes = $user_themes;
        $this->markDirty();
    }

    public function getUserThemes(): string
    {
        return $this->user_themes;
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

    public function setText(string $text)
    {
        $this->text = $text;
        $this->markDirty();
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getModelName(): string
    {
        return 'UserText';
    }
}
