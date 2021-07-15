<?php
/**
 * Коллекция для объектов типа Venue
 */
namespace Collections;

use DomainModel\VenueModel;
use Mapper\VenueMapper;

class VenueCollection extends Collection
{
    /**
     * Возвращает имя класса модели
     * Используется для проверки, дочерняя коллекция соответстует конкретному типу модели и может содержать только объекты её типа
     * 
     * @return string
     */
    public function targetClass(): string
    {
        return VenueModel::class;
    }

    /**
     * Возвращает имя класса Маппера
     * Используется для проверки, дочерняя коллекция должна получить конкретный тип маппера
     * 
     * @return string
     */
    public function targetMapperClass(): string
    {
        return VenueMapper::class;
    }
}