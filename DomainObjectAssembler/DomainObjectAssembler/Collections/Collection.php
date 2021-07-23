<?php
/**
 * Класс для получения коллеций и большого количества объектов модели.
 * Объявлен абстрактным, а следовательно, необходимо предоставить отдельные его реализации для каждого класса предметной области
 * 
 * можно добавить методы elementAt(), deleteAt(), count()
 * можно применить генератор вместо итератора
 * 
 * Мы скрыли за этим интерфейсом тот секретный факт, что исходные данные, извлекаемые из базы данных, могли быть еще не использованы
 * для получения экземпляра объекта предметной области в момент обращения к нему из клиентского кода.
 * 
 * Помимо типовой безопасности, особое преимущество от применения для свойств не массива, а коллекции заключается в возможности 
 * откорректировать процесс загрузки по требованию, если в этом возникнет потребность
 */
namespace DomainObjectAssembler\Collections;

use DomainObjectAssembler\Mapper\Mapper;
use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\Factories\DomainObjectFactory\DomainObjectFactory;
// use DomainObjectAssembler\Factories\DomainObjectFactory\DefaultTextObjectFactory;

abstract class Collection implements \Iterator
{
    protected $factory = null;

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
    protected $total = 0;

    /**
     * Mассив данных
     * 
     * @var array
     */
    protected $raw = [] ;

    /**
     * Указатель
     * 
     * @var int
     */
    private $pointer = 0;

    /**
     * Массив объектов модели
     * 
     * @var array
     */
    private $objects = [];

    /**
     * Возвращает имя класса модели
     * Используется для проверки, дочерняя коллекция соответстует конкретному типу модели и может содержать только объекты её типа
     * 
     * @return string
     */
    abstract public function targetClass(): string;

    /**
     * Возвращает имя класса фабрики моделей
     * Используется для проверки, дочерняя коллекция должна получить фабрику для своего типа моделей
     * 
     * @return string
     */
    abstract public function targetFactoryClass(): string;

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
    public function __construct(array $raw = [], DomainObjectFactory $factory = null)
    {
        //Если есть $raw, обязательно нужен Mapper для обработки
        if (count($raw) && is_null($factory)) {
            throw new \Exception("Collection(109): Нужен объект типа DomainObjectFactory для создания объектов");
        }

        //Получаем имя класса фабрики в дочерней реализации
        $class = $this->targetFactoryClass();

        //Если массив данных не пуст, проверяем, что фабрика нужного класса
        if ( count($raw) && !($factory instanceof $class)) {
            throw new \Exception("Collection(116): Коллекции необходимо передать маппер типа {$class}");
        }

        $this->raw     = $raw;
        $this->total   = count($raw);
        $this->factory = $factory;
    }



        // public function __construct(array $raw = [], Mapper $mapper = null)
    // {
    //     //Если есть $raw, обязательно нужен Mapper для обработки
    //     if (count($raw) && is_null($mapper)) {
    //         throw new \Exception("Нужен объект типа Mapper для создания объектов");
    //     }

    //     //Получаем имя класса маппера в дочерней реализации
    //     $class = $this->targetMapperClass();
    //     //Если массив данных не пуст, проверяем, что маппер нужного класса
    //     if ( count($raw) && !($mapper instanceof $class)) {
    //         throw new \Exception("Коллекции необходимо передать маппер типа {$class}");
    //     }

    //     $this->raw    = $raw;
    //     $this->total  = count($raw);
    //     $this->mapper = $mapper;
    // }






    /**
     * Метод для добавления объектов модели в коллекцию
     * 
     * @param DomainModel\DomainModel $object
     * 
     * @return void
     */
    public function add(DomainModel $model)
    {
        //Получаем имя класса в дочерней реализации
        $class = $this->targetClass();
        //В коллекцию можно добавить только объекты соответствующего класса
        if (! ($model instanceof $class)) {
            throw new \Exception("Collection(): Это коллекция {$class}, можно добавлять только соответствующие модели");
        }

        //Для шаблона Lazy Load
        $this->notifyAccess();

        //Добавить объект в массив объектов
        $this->objects[$this->total] = $model;
        $this->total++;
    }

    protected function notifyAccess()
    {
        //намеренно оставлен пустым!
        //Для шаблона Lazy Load, реализуется дочерним классом
    }

    /**
     * Получить объект модели по номеру в массиве
     * 
     * @return null|DomainMidel\Domainmodel
     */
    private function getRow(int $num)
    {
        $this->notifyAccess();

        //Если элемента не существует, возвращаем NullModel
        if ($num >= $this->total || $num < 0) {
            return new NullModel;
        }

        //Если элемент есть в массиве объектов, берём его оттуда
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }

        //Иначе находим данные в массиве данных и создаём объект модели типа DomainModel
        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->factory->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
    }

    // TODO: Вроде не нужный метод
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
        $model = $this->getRow($this->pointer);

        if (! is_null($model)) {
            $this->pointer++;
        }
        return $model;
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
