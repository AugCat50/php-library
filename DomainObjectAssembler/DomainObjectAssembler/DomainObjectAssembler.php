<?php 
namespace DomainObjectAssembler;

use DomainObjectAssembler\Registry\Registry;
use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\IdentityObject\IdentityObject;

class DomainObjectAssembler
{
    private $factory        = null;
    private $statements     = [];
    private $pdo            = null;
    private $identityObject = null;

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
        if(is_null($this->identityObject)){
            $this->identityObject = $this->factory->getIdentityObject();
        }
        
        return $this->identityObject;
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
        $status = $stmt->execute($values);

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

    /**
     * Делегирует полномочия методу Update, поскольку оператор INSERT генерирует фабрика Updete в случае, если id модели пуст.
     * Если id модели пуст, значит это новый объект, которого нет в БД.
     */
    public function insert(DomainModel $model)
    {
        $this->update($model);
        // $insFactory = $this->factory->getInsertFactory();
    }

    public function update(DomainModel $model)
    {
        $updFactory = $this->factory->getUpdateFactory();

        //Сгенерировать массив ['запрос', [данные]] в фабрике запросов
        $query      = $updFactory->newUpdate($model);

        //подготовить запрос prepare
        $stmt = $this->getStatement($query[0]);

        //Выполнить запрос
        $stmt->execute($query[1]);
        // d($query);
    }

    /**
     * Если id берётся из модели, рекомендую удалить и объект модели вручную,
     * дабы не получить не связанный с БД объект модели в системе. Возможно пофикшу в Identity Map
     */
    public function delete(int $id)
    {
        //Чтобы не грузить клиента созданием Identity Object, когда это можно сделать автоматически
        $idObj = $this->getIdentityObject();
        $idObj->field('id')->eq($id);

        $delFactory = $this->factory->getDeletionFactory();
        $queryStr   = $delFactory->newDeletion($idObj);

        //подготовить запрос prepare
        $stmt = $this->getStatement($queryStr);

        //Выполнить запрос
        $stmt->execute();
    }
}
