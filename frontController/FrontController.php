<?php
use app\Registry\Registry;

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
        // $frontController->handleRequest();
        // d($frontController);
    }

    /**
     * Получаем из реестра объект Application Helper'а и делегируем ему инициализацию приложения
     */
    private function init()
    {
        $this->reg->getApplicationHelper()->init();
    }

    private function handleRequest()
    {
        // $request = $reg->getRequest();
        // $resolver = new CommandResolver();
        // $cmd = $resolver->getCommand($request);
        // $cmd->execute($request);
    }
}
