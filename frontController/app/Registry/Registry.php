<?php 
/**
 * Реестр
 * 
 * Этот шаблон предназначен для того, чтобы предоставлять доступ к объектам во всей системе, 
 * позволяет обойти каналы связи в системе и изабвиться от длинных путей передачи информации.
 * Лучшая альтернатива глобальным переменным.
 * 
 * Является синглтоном.
 */
namespace app\Registry;

use app\Conf\Conf;
use app\Traits\SingletonTrait;
use app\ApplicationHelper\ApplicationHelper;

//Подключение классов наследуемых от Request
use app\Abstract\Request;
use app\Http\Requests\HttpRequest;
use app\Cli\Requests\CliRequest;

class Registry 
{
    use SingletonTrait;

    /**
     * Объект Application Helper
     * 
     * @var app\ApplicationHelper\ApplicationHelper $applicationHelper
     */
    private $applicationHelper = null;

    /**
     * Объект Conf с переменными окружения
     * 
     * @var app\Conf\Conf $enviroment
     */
    private $enviroment;

    /**
     * Объект Conf с настройками роутов
     * 
     * @var app\Conf\Conf $routes
     */
    private $routes;

    /**
     * Объект реквеста. Из namespace app\Http или app\Cli ,в зависимости от запроса, поступившего в систему
     */
    private $request;

    /**
     * Создаёт, сохраняет и возвращает объект Application Helper
     * 
     * @return app\ApplicationHelper\ApplicationHelper
     */
    public function getApplicationHelper(): ApplicationHelper
    {
        if (is_null($this->applicationHelper)){
            $this->applicationHelper = new ApplicationHelper();
        }
        return $this->applicationHelper;
    }

    /**
     * Установить объект, содержащий массив настроек окружения
     * 
     * @param app\Conf\Conf $enviroment
     */
    public function setEnviroment(Conf $enviroment)
    {
        $this->enviroment = $enviroment;
    }
    
    /**
     * Получить объект, содержащий массив настроек окружения
     * 
     * @return app\Conf\Conf
     */
    public function getEnviroment()
    {
        return $this->enviroment;
    }

    /**
     * Установить объект, содержащий массив зависимостей запрос - комманда
     * 
     * @param app\Conf\Conf $routes
     */
    public function setRoutes(Conf $routes)
    {
        $this->routes = $routes;
    }
    
    /**
     * Получить объект, содержащий массив зависимостей запрос - комманда
     * 
     * @return app\Conf\Conf
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
    
    public function getRequest($connection): Request
    {
        if (is_null($this->reuest[$connection])) {
            throw new \Exception('Объект типа request не задан.');
            return $this->request;
        }
    }

    // На удаление
    // public function getAccessManager()
    // {

    // }
}
