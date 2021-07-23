<?php
namespace DomainObjectAssembler\DomainModel;

class DefaultTextModel extends DomainModel
{
    private $name;
    private $text;
    private $hidden;

    public function __construct(int $id, string $name, string $text, bool $hidden)
    {
        $this->name   = $name;
        $this->text   = $text;
        $this->hidden = $hidden;
        parent::__construct($id);
        //Посмотреть стек
        // d($this);
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

    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
        $this->markDirty();
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function getModelName(): string
    {
        return 'DefaultText';
    }
}
