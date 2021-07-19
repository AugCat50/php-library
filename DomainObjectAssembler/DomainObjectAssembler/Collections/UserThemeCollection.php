<?php 
namespace DomainObjectAssembler\Collections;

use DomainObjectAssembler\Factories\DomainObjectFactory\UserThemeObjectFactory;

class UserThemeCollection extends Collection
{
    /**
     * Возвращает имя класса модели
     * Используется для проверки, дочерняя коллекция соответстует конкретному типу модели и может содержать только объекты её типа
     * 
     * @return string
     */
    public function targetClass(): string
    {
        return UserThemeModel::class;
    }

    /**
     * Возвращает имя класса фабрики моделей
     * Используется для проверки, дочерняя коллекция должна получить фабрику для своего типа моделей
     * 
     * @return string
     */
    public function targetFactoryClass(): string
    {
        return UserThemeObjectFactory::class;
    }
}
