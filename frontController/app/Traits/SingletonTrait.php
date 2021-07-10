<?php
namespace app\Traits;

trait SingletonTrait
{
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

    public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static());
    }
}
