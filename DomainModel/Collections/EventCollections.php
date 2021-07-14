<?php
namespace Collections;

class EventCollection extends Collection
{
    public function targetClass(): string
    {
        return EventModel::class;
    }
}