<?php
namespace app\CommandResolver;

use app\Requests\Request;
use app\Commands\Command;
use app\Registry\Registry;

class CommandResolver{
    private static $refcmd     = null;
    private static $defaultcmd = Defaultcommand::class;

    public function __construct()
    {
        // этот объект можно сделать конфигурируемым
        self::$refcmd = new \ReflectionClass(Command::class);
        d(self::$refcmd);
    }

    public function getCommand(Request $request): Command
    {
        $reg      = Registry::getInstance();
        $commands = $reg->getRoutes();
        $path     = $request->getPath();
        $class    = $commands->get($path);

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
