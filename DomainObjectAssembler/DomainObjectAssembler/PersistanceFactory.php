<?php
/**
 * Абстрактная фабрика, создаёт группу фабрик по имени класса модели. 
 * Предоставляет: 
 * фабрику модели
 * фабрики запросов (insert, select, update, delete)
 * 
 */
namespace DomainObjectAssembler;

use DomainObjectAssembler\IdentityObject\IdentityObject;
use DomainObjectAssembler\SelectQueriesFactory\SelectionFactory;

class PersistanceFactory
{
    /**
     * Список существующих в системе моделей
     * 
     * @var array
     */
    private $enforce = ['DefaultText', 'User', 'UserText', 'UserTheme', 'Temp'];

    /**
     * Имя модели без присавки Model
     * 
     * @var string
     */
    private $modelClass;

    public function __construct(string $modelName)
    {
        if(! in_array($modelName, $this->enforce)){
            $str = implode(', ', $this->enforce);
            throw new \Exception("PersistanceFactory(12): имя модели $modelName отсутствует в списке разрешённых: $str");
        }
        
        $this->modelClass = $modelName;
    }

    public function getIdentityObject(): IdentityObject
    {
        $className = 'IdentityObject\\' . $this->modelClass .'IdentityObject';
        return $this->reflection($className);
    }

    /**
     * Получить объект фабрики модели
     * 
     * @return PersistanceFactory\DomainObjectFactory\DomainObjectFactory
     */
    public function getModelFactory()
    {
        $className = 'PersistanceFactory\DomainObjectFactory\\' . $this->modelClass .'ObjectFactory';
        return $this->reflection($className);
    }

    /**
     * Получить объект фабрики SELECT запросов
     * 
     * @return PersistanceFactory\SelectQueriesFactory\SelectQueriesFactory
     */
    public function getSelectionFactory()
    {
        // $className = 'PersistanceFactory\SelectQueriesFactory\\' . $this->modelClass .'SelectionFactory';
        // return $this->reflection($className);

        // $className = 'PersistanceFactory\SelectQueriesFactory\SelectionFactory';
        // return $this->reflection($className);
        return new SelectionFactory();
    }

    /**
     * Получить объект фабрики INSERT INTO запросов
     * 
     * @return PersistanceFactory\InsertQueriesFactory\InsertQueriesFactory
     */
    public function getInsertFactory()
    {
        $className = 'PersistanceFactory\InsertQueriesFactory\\' . $this->modelClass .'InsertFactory';
        return $this->reflection($className);
    }

    /**
     * Получить объект фабрики UPDATE запросов
     * 
     * @return PersistanceFactory\UpdateQueriesFactory\UpdateQueriesFactory
     */
    public function getUpdateFactory()
    {
        $className = 'PersistanceFactory\UpdateQueriesFactory\\' . $this->modelClass .'UpdateFactory';
        return $this->reflection($className);
    }

    /**
     * Получить объект фабрики DELETE запросов
     * 
     * @return PersistanceFactory\DeleteQueriesFactory\DeleteQueriesFactory
     */
    public function getDeleteFactory()
    {
        $className = 'PersistanceFactory\DeleteQueriesFactory\\' . $this->modelClass .'DeleteFactory';
        return $this->reflection($className);
    }

    public function getCollection()
    {

    }

    /**
     * Получает полное имя класса, возвращает его объект
     * 
     * @param string $className
     * @return object
     */
    private function reflection(string $className)
    {
        $class   = new \ReflectionClass($className);
        $factory = $class->newInstance();

        return $factory;
    }
}
