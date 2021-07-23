<?php
namespace DomainObjectAssembler\DomainModel;

class TempModel extends DomainModel
{
    private $user_id;
    private $key_act;
    private $mail;

    public function __construct(int $id, int $user_id, string $key_act, string $mail)
    {
        $this->user_id = $user_id;
        $this->key_act = $key_act;
        $this->mail    = $mail;
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

    public function setKeyAct(string $key_act)
    {
        $this->key_act = $key_act;
        $this->markDirty();
    }

    public function getKeyAct(): string
    {
        return $this->key_act;
    }

    public function setMail(string $mail)
    {
        $this->mail = $mail;
        $this->markDirty();
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getModelName(): string
    {
        return 'Temp';
    }
}
