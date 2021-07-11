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
namespace app\Registry;

use app\Traits\SingletonTrait;

use app\Conf\Conf;
use app\ApplicationHelper\ApplicationHelper;
use app\Requests\Request;

class Registry 
{
    use SingletonTrait;

    /**
     * Объект Application Helper
     * 
     * @var app\ApplicationHelper\ApplicationHelper
     */
    private $applicationHelper = null;

    /**
     * Объект Conf с переменными окружения
     * 
     * @var Conf
     */
    private $enviroment;

    /**
     * Объект Conf с настройками роутов
     * 
     * @var Conf
     */
    private $routes;

    /**
     * Объект реквест. app\Requests\Http\ или app\Requests\Cli\ ,
     * в зависимости от запроса, поступившего в систему
     * @var Request
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
     * 
     * @return void
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
    public function getEnviroment(): Conf
    {
        return $this->enviroment;
    }

    /**
     * Установить объект, содержащий массив зависимостей запрос - комманда
     * 
     * @param app\Conf\Conf $routes
     * 
     * @return void
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
    public function getRoutes(): Conf
    {
        return $this->routes;
    }

    /**
     * Установить объект Request
     * 
     * @param app\Request $request
     * 
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Получить объект Request

     * 
     * @return app\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
