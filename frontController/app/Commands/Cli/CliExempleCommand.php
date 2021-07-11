<?php
/**
 * Комманда для обработки запросов коммандной строки
 */
namespace app\Commands\Cli;

use app\Commands\Command;
use app\Requests\Request;

class CliExempleCommand extends Command
{
    /**
     * Вызывыть логику приложения тут.
     * 
     * @return void|mixed
     */
    public function execute(Request $request)
    {
        return false;
    }
}
