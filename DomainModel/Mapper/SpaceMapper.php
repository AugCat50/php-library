<?php
/**
 * преобразователь данных по шаблону Data Mapper — это класс, который 
 * отвечает за управление передачей данных из базы данных в отдельный объект SpaceModel
 * 
 * если встанет вопрос эффективности, то следует полностью реорганизовать исходный код класса SpaceMapper 
 * и получить все необходимые данные за один проход с помощью конструкции JOIN SQL-запроса
 */
namespace Mapper;

use Collections\Collection;
use DomainModel\SpaceModel;
use DomainModel\VenueModel;
use DomainModel\DomainModel;
use Collections\SpaceCollection;
// use Collections\EventCollection;

class SpaceMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    private $selectAllStmt;
    private $findByVenueStmt;

    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = $this->pdo->prepare("SELECT * FROM space WHERE id=?");
        $this->updateStmt = $this->pdo->prepare("UPDATE space SET name=?, venue=?, id=? WHERE id=?");
        $this->insertStmt = $this->pdo->prepare("INSERT INTO space ( name, venue ) VALUES ( ?, ? )");


        $this->selectAllStmt   = $this->pdo->prepare("SELECT * FROM space");

        //Здесь внедрен еще один оператор, $findByVenueStmt, предназначенный для 
        //выборки объектов типа Space, характерных для отдельного объекта типа Venue.
        $this->findByVenueStmt = $this->pdo->prepare("SELECT * FROM space where venue=?");
    }

    /**
     * Получить имя обрабатываемой этим маппером модели для проверки
     * Проверка в суперклассе Mapper
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return SpaceModel::class;
    }

    /**
     * Создать объект модели соответствующей мапперу
     * 
     * Создаётся коллекция типа EventCollection 
     * для каждого создаваемого объекта типа SpaceModel.
     * 
     * @return DomainModel\SpaceModel
     */
    protected function doCreateObject(array $array): DomainModel
    {
        $spaceModel  = new SpaceModel( 
                            (int)   $array['id'], 
                            (string)$array['name'], 
                            (int)   $array['venue'] 
                        );

        //Создаём коллекцию подконтрольных данной Space объектов Event

        //Создать маппер для коллекции EventCollection
        $eventMapper = new EventMapper();

        //Получить данные из БД методом маппера, упаковать в коллекцию, вместе с объектом маппером
        $eventCollection = $eventMapper->findBySpace($array['id']);

        //Записать в модель полученную коллекцию
        $spaceModel->setEvents($eventCollection);

        return $spaceModel;
    }


    /**
     * получить подготовленный оператор SELECT языка SQL
     * Выборку производит метод DomainModel::find()
     */
    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    /**
     * получить подготовленный оператор SELECT языка SQL
     * Выборку производит метод DomainModel::findAll()
     */
    protected function selectAllStmt(): \PDOStatement
    {
        return $this->selectAllStmt;
    }

    /**
     * Операция добавления новой строки в таблицу space
     * 
     * @param DomainModel\SpaceModel $model
     * 
     * @return void
     */
    protected function doInsert(DomainModel $model)
    {
        //Получение данных из SpaceModel
        $data = [
            $model->getName(),
            $model->getVenue()
        ];
        $this->insertStmt->execute($data);

        //Здесь происходит магия. Вне зависимости от того, какой был задан id у модели,
        //id будет задан автоматически, и записан в поле id модели
        $id = $this->pdo->lastInsertId();
        $model->setId((int)$id);
    }

    /**
     * Операция обновления строки в таблице space
     * 
     * @param DomainModel\SpaceModel $model
     * 
     * @return void
     */
    protected function doUpdate(DomainModel $model)
    {
        //Получение данных из SpaceModel
        //Запросу надо 2 раза передать Id
        $values = [
            $model->getName(),
            $model->getVenue(),
            $model->getId(),
            $model->getId()
        ] ;
        $this->updateStmt->execute($values);
    }



    /**
     * Выбрать из таблицы space все строки у которых поле venue = $vid
     * И создать коллекцию с этими данными
     * 
     * По сути, выбирает все подчинённые объекты Space объекту Venue
     * 
     * @return Collections\EventCollection
     */
    public function findByVenue($vid): Collection
    {
        $this->findByVenueStmt->execute([$vid]);

        //Коллекции необходимо передать массив с данными и объект маппер, для создания из данных объектов
        //PDOStatement::fetchAll — Возвращает массив, содержащий все строки результирующего набора 
        return new SpaceCollection( $this->findByVenueStmt->fetchAll(), $this );
    }

//TODO: Работа с коллекциями









    public function getCollection(array $raw): Collection
    {
        return new SpaceCollection($raw, $this);
    }


}

