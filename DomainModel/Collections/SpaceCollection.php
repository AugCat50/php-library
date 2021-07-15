<?php
/**
 * Коллекция для объектов типа Space
 */
namespace Collections;

use DomainModel\SpaceModel;
use Mapper\SpaceMapper;

class SpaceCollection extends Collection
{
    /**
     * Возвращает имя класса модели
     * Используется для проверки, дочерняя коллекция соответстует конкретному типу модели и может содержать только объекты её типа
     * 
     * @return string
     */
    public function targetClass(): string
    {
        return SpaceModel::class;
    }

    /**
     * Возвращает имя класса Маппера
     * Используется для проверки, дочерняя коллекция должна получить конкретный тип маппера
     * 
     * @return string
     */
    public function targetMapperClass(): string
    {
        return SpaceMapper::class;
    }
}
