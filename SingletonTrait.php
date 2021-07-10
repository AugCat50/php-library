<?php

// namespace ;

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

    /**
     * запрет на десериализацию
     * Парсер выдаёт варнинг. Матод должен быть паблик.
     */
    // private function __wakeup()
    // {
    //     //
    // }

    public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static());
    }
}
