<?php 
namespace DomainModel;

use Mapper\Mapper;

abstract class Collection implements \Iterator
{
    protected $mapper;
    protected $total   = 0;
    protected $raw     = [] ;
    private   $pointer = 0;
    private   $objects = [];

    public function __construct(array $raw = [], Mapper $mapper = null)
    {
        $this->raw = $raw;
        $this->total = count($raw);

        if (count($raw) && is_null($mapper)) {
            throw new \Exception("Нужен объект типа Mapper для создания объектов");
        }

        $this->mapper = $mapper;
    }

    public function add(DomainModel $object)
    {
        $class = $this->targetClass();

        if (! ($object instanceof $class)) {
            throw new \Exception("Это коллекция {$class}");
        }

        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    abstract public function targetclass(): string;

    protected function notifyAccess()
    {
        // намеренно оставлен пустым!
    }

    private function getRow($num)
    {
        $this->notifyAccess();
        if ($num >= $this->total || $num < 0) {
            return null;
        }

        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }

        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->mapper->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
    }

    public function rewind()
    {
        $this->pointer = 0;
    }
    
    public function current()
    {
        return $this->getRow($this->pointer);
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        $row = $this->getRow($this->pointer);

        if (! is_null($row)) {
            $this->pointer++;
        }
    }

    public function valid()
    {
        return (! is_null($this->current()));
    }
}
