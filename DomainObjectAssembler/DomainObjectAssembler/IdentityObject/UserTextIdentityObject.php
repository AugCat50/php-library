<?php 
namespace DomainObjectAssembler\IdentityObject;

class UserTextIdentityObject extends IdentityObject
{
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'user_id',  'user_themes', 'name', 'text'],
            'user_texts'
        );
    }
}