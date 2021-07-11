<?php
/**
 * Команда - управляющий класс, служащий для вызова логики приложения исходя из условий запроса.
 * Зависимости запрос - команда обозваны в системе роутами
 */
namespace app\Commands;

use app\Requests\Request;

abstract class Command
{
    // abstract public function execute(CommandContext $context): bool;
    
    /**
     * Вызывыть логику приложения тут.
     */
    abstract public function execute(Request $request);
}
