<?php
namespace Collections;

class SpaceCollection extends Collection
{
    public function targetClass(): string
    {
        return SpaceModel::class;
    }
}
