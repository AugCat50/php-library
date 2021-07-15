<?php
/**
 * Коллекция для объектов типа Event
 */
namespace Collections;

use DomainModel\EventModel;
use Mapper\EventMapper;

class EventCollection extends Collection
{
    /**
     * Возвращает имя класса модели
     * Используется для проверки, дочерняя коллекция соответстует конкретному типу модели и может содержать только объекты её типа
     * 
     * @return string
     */
    public function targetClass(): string
    {
        return EventModel::class;
    }

    /**
     * Возвращает имя класса Маппера
     * Используется для проверки, дочерняя коллекция должна получить конкретный тип маппера
     * 
     * @return string
     */
    public function targetMapperClass(): string
    {
        return EventMapper::class;
    }
}