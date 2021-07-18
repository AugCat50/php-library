<?php
namespace DomainObjectAssembler\Traits;

trait SingletonTrait
{
    /**
     * Хранилище экземпляра
     * 
     * @var SingletonTrait;
     */
    private static $instance = null;

    /**
     * запрет на создание
     * 
     * SingletonTrait constructor
     */
    private function __construct()
    {
        //
    }

    /**
     * запрет на клонирование
     */
    private function __clone()
    {
        //
    }

    /**
     * Получить экземпляр
     */
    public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static());
    }
}
