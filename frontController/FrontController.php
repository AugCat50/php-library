<?php
use app\Registry\Registry;
use app\CommandResolver\CommandResolver;
use app\Commands\CommandContext;

class FrontController
{
    /**
     * Экземпляр объекта Реестр
     * 
     * @var app\Registry\Registry
     */
    private $reg;

    /**
     * Получение экземпляра класса Реестр
     */
    private function __construct()
    {
        $this->reg = Registry::getInstance();
    }

    /**
     * Метод обёртка для инициализации системы и обработки запроса
     * 
     * @return void
     */
    public static function run()
    {
        $frontController = new FrontController();
        $frontController->init() ;
        $frontController->handleRequest();
        // d($frontController);
    }

    /**
     * Получаем из реестра объект Application Helper'а и делегируем ему инициализацию приложения
     * 
     * ApplicationHelper::init() парсит и устанавливает настройки и роуты и создаёт объект Request
     * Далее обращаться к параметрам HTTP-запроса исключительно с помощью вызова метода Request::getProperty()
     * 
     * @return void
     */
    private function init()
    {
        $this->reg->getApplicationHelper()->init();
    }

    /**
     * Обработка запроса вызывается здесь.
     * 
     * Контроллер, обращается к логике приложения, выполняя команду из объекта типа Command.
     * Этот объект выбирается в соответствии со структурой запрошенного URL.
     * 
     * @return void
     */
    private function handleRequest()
    {
        $request  = $this->reg->getRequest();
        $resolver = new CommandResolver();
        $cmd      = $resolver->getCommand($request);
        $msg      = $cmd->execute($request);
        echo $msg;
    }
}
