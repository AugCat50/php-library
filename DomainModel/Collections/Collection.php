<?php
/**
 * Класс для получения коллеций и большого количества объектов модели.
 * Объявлен абстрактным, а следовательно, необходимо предоставить отдельные его реализации для каждого класса предметной области
 * 
 * можно добавить методы elementAt(), deleteAt(), count()
 * можно применить генератор вместо итератора
 */
namespace Collections;

use Mapper\Mapper;
use DomainModel\DomainModel;

abstract class Collection implements \Iterator
{
    /**
     * Объект типа Mapper
     * 
     * @var Mapper\Mapper
     */
    protected $mapper;

    /**
     * Размер полученного массива данных
     * 
     * @var int
     */
    protected $total   = 0;

    /**
     * Mассив данных
     * 
     * @var array
     */
    protected $raw     = [] ;

    /**
     * Указатель
     * 
     * @var int
     */
    private   $pointer = 0;

    /**
     * Массив объектов модели
     * 
     * @var array
     */
    private   $objects = [];

    /**
     * Для получения имени класса модели
     * Дочерняя коллекция соответстует конкретному типу модели и может содержать только объекты её типа
     * 
     * @return string
     */
    abstract public function targetClass(): string;

    /**
     * В конструктор передаётся массив данных, полученный из БД и объект Mapper,
     * преобразующий каждую строку из таблицы в объект. 
     * Так же может быть вызыван без параметров. 
     * 
     * @param array         $raw
     * @param Mapper\Mapper $mapper
     * 
     * @return void
     */
    public function __construct(array $raw = [], Mapper $mapper = null)
    {
        //Если есть $raw, обязательно нужен Mapper для обработки
        if (count($raw) && is_null($mapper)) {
            throw new \Exception("Нужен объект типа Mapper для создания объектов");
        }

        $this->raw    = $raw;
        $this->total  = count($raw);
        $this->mapper = $mapper;
    }

    /**
     * Метод для добавления объектов модели в коллекцию
     * 
     * @param DomainModel\DomainModel $object
     * 
     * @return void
     */
    public function add(DomainModel $object)
    {
        //Получаем имя класса в дочерней реализации
        $class = $this->targetClass();
        //В коллекцию можно добавить только объекты соответствующего класса
        if (! ($object instanceof $class)) {
            throw new \Exception("Это коллекция {$class}");
        }

        $this->notifyAccess();

        //Добавить объект в массив объектов
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    protected function notifyAccess()
    {
        //намеренно оставлен пустым!
        //Для шаблона Lazy Load
    }

    /**
     * Получить объект модели по номеру в массиве
     */
    private function getRow($num)
    {
        $this->notifyAccess();
        if ($num >= $this->total || $num < 0) {
            return null;
        }

        //Если элемент есть в массиве объектов, берём его оттуда
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }

        //Иначе находим данные в массиве данных и создаём объект модели типа DomainModel
        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->mapper->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
    }

    public static function getCollection(string $type)
    {
        return [];
    }

    //Далее реализация методов итератора

    /**
     * Перемещает указатель в начало списка
     * 
     * @return void
     */
    public function rewind()
    {
        $this->pointer = 0;
    }
    
    /**
     * Возвращает элемент списка, находящийся на текущей позиции указателя
     * 
     * @return DomainModel\DomainModel
     */
    public function current()
    {
        return $this->getRow($this->pointer);
    }

    /**
     * Возвращает текущий ключ (т.е. значение указателя)
     * 
     * @return int
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Возвращает элемент списка, находящийся на текущей позиции указателя, перемещая указатель на следующую позицию
     * 
     * @return DomainModel\DomainModel
     */
    public function next()
    {
        $row = $this->getRow($this->pointer);

        if (! is_null($row)) {
            $this->pointer++;
        }
    }

    /**
     * Подтверждает, что на текущей позиции указателя находится элемент списка
     * 
     * @return bool
     */
    public function valid()
    {
        return (! is_null($this->current()));
    }
}
