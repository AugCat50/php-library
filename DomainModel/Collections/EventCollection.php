<?php
/**
 * Коллекция для объектов типа Event
 * 
 * Lazy Load
 * Идея состоит в том, чтобы создать объект-коллекцию типа Eventcollection,
 * где обращение к базе данных откладывается до тех пор, пока не будет сделан запрос на него.
 * 
 * Это означает, что клиентскому объекту (например, типа SpaceModel)
 * вообще не должно быть известно, что он содержит пустую коллекцию типа
 * Collection при своем первоначальном создании. По мере того как клиент начнет работу с данными, он будет содержать совершенно нормальную коллекцию
 * типа EventCollection.
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