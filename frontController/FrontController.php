<?php
use app\Registry\Registry;
use app\CommandResolver\CommandResolver;
use app\Commands\CommandContext;

class FrontController
{
    private $reg;

    private function __construct()
    {
        $this->reg = Registry::getInstance();
    }

    public static function run()
    {
        $frontController = new FrontController();
        $frontController->init() ;
        $frontController->handleRequest();
        // d($frontController);
    }

    /**
     * Получаем из реестра объект Application Helper'а и делегируем ему инициализацию приложения
     */
    private function init()
    {
        $this->reg->getApplicationHelper()->init();
    }

    /**
     * Обработка запроса начинается здесь.
     * 
     * Контроллер, обращается к логике приложения, выполняя команду из объекта типа Command.
     * Этот объект выбирается в соответствии со структурой запрошенного URL.
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
