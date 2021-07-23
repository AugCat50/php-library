<?php
namespace DomainObjectAssembler\DomainModel;

class UserModel extends DomainModel
{
    private $name;
    private $password;
    private $solt;
    private $mail;

    public function __construct(int $id, string $name, string $password, string $solt, string $mail)
    {
        $this->name     = $name;
        $this->password = $password;
        $this->solt     = $solt;
        $this->mail     = $mail;
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

    public function setPassword(string $password)
    {
        $this->password = $password;
        $this->markDirty();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setSolt(string $solt)
    {
        $this->solt = $solt;
        $this->markDirty();
    }

    public function getSolt(): string
    {
        return $this->solt;
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
        return 'User';
    }
}
