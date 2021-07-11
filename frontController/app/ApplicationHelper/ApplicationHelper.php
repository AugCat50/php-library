<?php
/**
 * ApplicationHelper
 * Не обязательный класс для шаблона FrontController, 
 * реализует стратегию инициализации переменных окружения и routes, зависимостей Url - комманда.
 */
namespace app\ApplicationHelper;

use app\Conf\Conf;
use app\Registry\Registry;
use app\Requests\Http\HttpRequest;
use app\Requests\Cli\CliRequest;

class ApplicationHelper
{
    /**
     * Путь к файлу - Настройки окружения
     * 
     * @var string
     */
    private $enviroment;

    /**
     * Путь к файлу - Роуты. Но по факту, зависимости настроек запроса с коммандой (command)
     * 
     * @var string
     */
    private $routes;

    /**
     * Реестр
     * 
     * @var app\Registry\Registry
     */
    private $reg;
    

    /**
     * Конструктор.
     * Установка путей к файлам настроек.
     */
    public function __construct()
    {
        $this->enviroment = dirname(dirname(__DIR__)) . "/env.ini";
        $this->routes     = dirname(dirname(__DIR__)) . "/routes/routes.ini";
        $this->reg        = Registry::getInstance();
    }
    
    /**
     * Инициализация приложения. Попытка выяснить, выполняется ли приложение в контексте веб или запущено из командной строки
     * 
     * @return void
     */
    public function init()
    {
        $this->setupOptions();

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $request = new HttpRequest();
        } else {
            $request = new CliRequest();
        }
        $this->reg->setRequest($request);
    }
    
    /**
     * Парсинг файлов настроек в объекты-обёртки Conf
     * Настройки окружения и роуты.
     * 
     * @return void
     */
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
