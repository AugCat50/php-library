<?php 

namespace DomainObjectAssembler\Collections;

use DomainObjectAssembler\Mapper\Mapper;
use DomainObjectAssembler\DomainObjectFactory\DomainObjectFactory;

class DeferredEventCollection extends EventCollection
{
    private $stmt;
    private $valueArray;
    private $run = false;

    /**
     * Класс просто сохраняет параметры и ждёт.
     * 
     * @param Mapper\Mapper $mapper
     * @param \PDOStatement $stmt_handle
     * 
     * Массив аргументов, который должен соответсвовать подготовленному оператору SQL.
     * @param array $valueArray
     * 
     * @return null
     */
    public function __construct (
                        DomainObjectFactory $factory,
                        \PDOStatement       $stmt_handle,
                        array               $valueArray
                    ) 
    {
        parent::__construct ([], $factory);
        $this->stmt       = $stmt_handle;
        $this->valueArray = $valueArray;
    }

    /**
     * Переопределяется пустой родительский метод
     * 
     * Он вызывается из любого внешне вызываемого метода
     * 
     * Если теперь кто-нибудь попытается обратиться к коллекции типа Collection, в этом классе станет известно, 
     * что пришло время перестать притворяться, будто он делает что-то полезное, и получить наконец какие-нибудь реальные данные.
     */
    public function notifyAccess()
    {
        if (! $this->run) {
            //С этой целью вызывается метод PDOStatement::execute()
            $this->stmt->execute($this->valueArray);

            //Вместе с вызовом метода PDOStatement::fetch() это дает возможность получить массив полей,
            //пригодных для передачи вызываемому методу Mapper::createObject().
            $this->raw   = $this->stmt->fetchAll();
            $this->total = count($this->raw);
        }

        $this->run = true;
    }
}
