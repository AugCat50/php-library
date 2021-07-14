<?php
/**
 * Очевидно, что данный класс должен действовать только вместе с классом VenueMapper.
 * Можно ввести проверку в конструкторе
 * 
 * Но из практических соображений он реализует умеренно типизированную коллекцию, особенно если дело касается шаблона Domain Model
 * ???
 */
namespace Collections;

use Collections\Collection;
use DomainModel\VenueModel;

class VenueCollection extends Collection
{
    public function targetClass(): string
    {
        return VenueModel::class;
    }
}
