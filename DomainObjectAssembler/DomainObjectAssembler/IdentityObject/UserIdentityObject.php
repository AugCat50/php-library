<?php 
namespace DomainObjectAssembler\IdentityObject;

class UserIdentityObject extends IdentityObject
{
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'name',  'password', 'solt', 'mail'],
            'users'
        );
    }
}