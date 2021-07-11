<?php
/**
 * Класс, отвечающий за поиск и получение команды соотвествующий url запроса.
 * Фабрика команд.
 */
namespace app\CommandResolver;

use app\Requests\Request;
use app\Commands\Command;
use app\Registry\Registry;

class CommandResolver{
    private static $refcmd     = null;
    private static $defaultcmd = DefaultCommand::class;

    public function __construct()
    {
        // этот объект можно сделать конфигурируемым
        self::$refcmd = new \ReflectionClass(Command::class);
    }

    /**
     * Метод, получающий объект команды соотвествующий url запроса
     * 
     * @param app\Requests\Request $request
     * 
     * @return app\Commands\Command
     */
    public function getCommand(Request $request): Command
    {
        //Получить объект реестр
        $reg = Registry::getInstance();

        //Получаем массив роутов
        $commands = $reg->getRoutes();

        //Получить url из объекта реквест
        $path = $request->getPath();

        //Ищем в массиве роутов соответствие url запроса, получаем полное имя класса
        $class = $commands->get($path);

        if (is_null($class)) {
            $request->addFeedback("Соответствие пути ". $path. " не обнаружено!");
            return new self::$defaultcmd();
        }

        if (! class_exists($class)) {
            $request->addFeedback("Класс ". $class. " не найден!");
            return new self::$defaultcmd();
        }

        $refclass = new \ReflectionClass ($class);

        if (! $refclass->isSubClassOf(self::$refcmd)) {
            $request->addFeedback("Команда ". $refclass. " не относится к классу Command!");
            return new self::$defaultcmd();
        }

        return $refclass->newInstance();
    }
}
