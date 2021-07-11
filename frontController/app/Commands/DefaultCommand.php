<?php
/**
 * Базовая команда, вызываемая в случае пустого запроса
 */
namespace app\Commands;

use app\Requests\Request;

class DefaultCommand extends Command
{
    /**
     * Можно сделать что угодно. Редирект на главную страницу, сообщение о ощибке; всё, что позволит фантазия
     * 
     * @return void|mixed
     */
    public function execute(Request $request)
    {
        // $request->addFeedback('Добро пожаловать в систему!');
        return "Hello world! <br>default command";
    }
}
