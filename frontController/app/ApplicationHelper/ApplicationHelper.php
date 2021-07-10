<?php
/**
 * ApplicationHelper
 * Не обязательный класс для шаблона FrontController.
 * Его задача - обработать настройки приложения. Загрузить переменные окружения и зависимости параметр запроса - комманда.
 */
namespace app\ApplicationHelper;

use app\Conf\Conf;
use app\Registry\Registry;
// use app\Abstract\Request;
use app\Http\Requests\HttpRequest;
use app\Cli\Requests\CliRequest;

class ApplicationHelper
{
    /**
     * Путь к файлу - Настройки окружения
     */
    private $enviroment;

    /**
     * Путь к файлу - Роуты. Но по факту, зависимости настроек запроса с коммандой (command)
     */
    private $routes;

    /**
     * Реестр
     */
    private $reg;
    

    public function __construct()
    {
        $this->enviroment = dirname(dirname(__DIR__)) . "/env.ini";
        $this->routes     = dirname(dirname(__DIR__)) . "/routes/routes.ini";
        $this->reg        = Registry::getInstance();
    }
    
    public function init()
    {

        $this->setupOptions();

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $request = new HttpRequest();
        } else {
            $request = new CliRequest();
        }
        // d($request, 1);
        $this->reg->setRequest($request);
    }
    
    private function setupOptions()
    {
        if(!file_exists($this->enviroment)) {
            throw new \Exception ("Файл конфигурации окружения не найден!\n");
        }
        if(!file_exists($this->routes)) {
            throw new \Exception ("Файл конфигурации роутов не найден!\n");
        }
        
        //массив типа Conf для хранения общих значений конfигурации
        $env = parse_ini_file($this->enviroment, true);
        $eConf = new Conf($env['config']);
        $this->reg->setEnviroment($eConf);
        // $this->config = $conf;
        
        //массив типа Conf для преобразования путей URL в классы типа Command
        $rts = parse_ini_file($this->routes, true);
        $rConf = new Conf($rts['commands']);
        $this->reg->setRoutes($rConf);
    }
}
