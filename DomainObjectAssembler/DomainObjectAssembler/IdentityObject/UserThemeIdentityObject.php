<?php 
namespace DomainObjectAssembler\IdentityObject;

class UserThemeIdentityObject extends IdentityObject
{
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'user_id',  'name'],
            'user_themes'
        );
    }
}