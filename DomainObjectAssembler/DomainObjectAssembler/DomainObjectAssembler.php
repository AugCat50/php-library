<?php 
namespace DomainObjectAssembler;

use DomainObjectAssembler\Registry\Registry;
use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\IdentityObject\IdentityObject;

class DomainObjectAssembler
{
    private $factory    = null;
    private $statements = [];
    private $pdo        = null;

    public function __construct(string $modelName)
    {
        $this->factory = new PersistanceFactory($modelName);
        $reg           = Registry::getInstance();
        $this->pdo     = $reg->getPdo();
        // $this->factory->getModelFactory();
    }

    /**
     * Делегируем PersistanceFactory получение объекта идентичности
     */
    public function getIdentityObject(): IdentityObject
    {
        $identityObject = $this->factory->getIdentityObject();
        return $identityObject;
    }
    
    /**
     * Получает объект модели по текущему указателю коллекции и увеличивает казатель на 1
     * По идее, у объекта коллекции уже будет увеличен указатель и в другом месте можно сразу получить модель
     * по следующей строке.
     * 
     * @param  DomainObjectAssembler\IdentityObject\IdentityObject $idobj
     * 
     * @return DomainObjectAssembler\DomainModel\DomainModel
     */
    public function findOne(IdentityObject $idobj): DomainModel
    {
        $collection = $this->find($idobj);

        return $collection->next();
    }

    /**
     * Получает коллекцию по запросу, сконфигурированному объектом идентичности и сгенерированному фабрикой запросов SelectionFactory.
     * 
     * @param  DomainObjectAssembler\IdentityObject\IdentityObject $idobj
     * 
     * @return DomainObjectAssembler\Collections\Collection
     */
    public function find(IdentityObject $idobj): Collection
    {
        //Получить объект SelectionFactory
        $selfact = $this->factory->getSelectionFactory();

        //Полоучить массив {[0] => 'запрос', [1] => переменные} в объекте SelectionFactory
        //Получается массив готовый для prepare, типа:
        //[0] => "SELECT id, name, text, hidden FROM default_texts WHERE name = ? AND id = ?"
        //[1] => [0] => 'имя', [1] => int(4)
        list ($selection, $values) = $selfact->newSelection($idobj);

        //подготовить запрос prepare
        $stmt = $this->getStatement($selection);

        //Выполнить запрос
        $stmt->execute($values);

        //Извлечь массив данных
        $raw = $stmt->fetchAll();
        return $this->factory->getCollection($raw);
    }

    /**
     * Закинуть строку запроса из фабрики запросов и выполнить pdo::prepare()
     * Кешируем подготовленный запрос в массив [строка] = подготовленный запрос.
     * 
     * @param string $str
     * 
     * @return \PDOStatement
     */
    public function getStatement(string $str): \PDOStatement
    {
        if (! isset($this->statements[$str])) {
            $this->statements[$str] = $this->pdo->prepare($str);
        }
        return $this->statements[$str];
    }

    public function insert()
    {
        $insFactory = $this->factory->getInsertFactory();
    }

    public function update()
    {
        $updFactory = $this->factory->getUpdateFactory();
    }

    public function delete()
    {
        $delFactory = $this->factory->getDeleteFactory();
    }
}
