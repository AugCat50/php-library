<?php 
/**
 * Комманда для обработки http запросов
 */
namespace app\Commands\Http;

use app\Commands\Command;
use app\Requests\Request;

class HttpExempleCommand extends Command
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
