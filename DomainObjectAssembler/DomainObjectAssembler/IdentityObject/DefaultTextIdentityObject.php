<?php 
namespace DomainObjectAssembler\IdentityObject;

class DefaultTextIdentityObject extends IdentityObject
{
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'name',  'text', 'hidden'],
            'default_texts'
        );
    }
}