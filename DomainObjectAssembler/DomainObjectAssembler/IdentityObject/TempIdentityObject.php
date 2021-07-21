<?php 
namespace DomainObjectAssembler\IdentityObject;

class TempIdentityObject extends IdentityObject
{
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'user_id',  'key_act', 'mail'],
            'temps'
        );
    }
}