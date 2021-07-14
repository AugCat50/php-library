<?php 
/**
 * Реестр
 * 
 * Этот шаблон предназначен для того, чтобы предоставлять доступ к объектам во всей системе, 
 * позволяет обойти каналы связи и изабвиться от длинных путей передачи информации.
 * Лучшая альтернатива глобальным переменным.
 * 
 * Является синглтоном.
 * (Камни не кидать)
 */
namespace Registry;

use Traits\SingletonTrait;

use Mapper\EventMapper;
use Mapper\VenueMapper;
use Mapper\SpaceMapper;

use Collections\VenueCollection;
use Collections\SpaceCollection;
use Collections\EventCollection;

// use app\Conf\Conf;
// use app\ApplicationHelper\ApplicationHelper;
// use app\Requests\Request;

class Registry 
{
    use SingletonTrait;

    private $pdo;
    private $venueCollection = null;

    /**
     * Записать в реестр объект PDO
     * Для простоты, создаю здесь. В реальном проекте надо создавать классом инициализации системы
     */
    public function setPdo()
    {
        try {
            $pdo = new \PDO('mysql:host=localhost;dbname=domain-model', 'root', '');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e){
            echo "Соединение с БД не успешно: " . $e->getMessage() . "<br>";
            exit;
        }
        $this->pdo = $pdo;
    }

    public function getPdo()
    {
        return $this->pdo;
    }


    //Стоит вынести это всё в абстрактную фабрику

    /**
     * Получить объект коллекции
     * 
     * Все дело в том, что по шаблону Domain Model необходимо получить экземпляры типа Collection, а в какой-то момент, 
     * возможно, потребуется сменить реализацию, особенно для целей тестирования. По мере расширения проектируемой системы 
     * эту обязанность можно поручить специально выделенной фабрике. Но на стадии разработки лучше выбрать простейший подход, 
     * применяя шаблон Registry для создания большинства объектов.
     * 
     * @return Collections\VenueCollection
     */
    public function getVenueCollection()
    {
        if(is_null($this->venueCollection)){
            $this->venueCollection = new VenueCollection();
        }
        return $this->venueCollection;
    }

    public function getSpaceCollection(): SpaceCollection
    {
        return new SpaceCollection();
    }

    public function getEventCollection(): EventCollection
    {
        return new EventCollection();
    }

    public function getVenueMapper(): VenueMapper
    {
        return new VenueMapper();
    }

    public function getSpaceMapper(): SpaceMapper
    {
        return new SpaceMapper();
    }

    public function getEventMapper(): EventMapper
    {
        return new EventMapper();
    }
}
