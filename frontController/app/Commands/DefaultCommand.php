<?php 
namespace app\Commands;

use app\Requests\Request;

class DefaultCommand extends Command
{
    public function execute(Request $request)
    {
        // $request->addFeedback('Добро пожаловать в систему!');
        return "Hello world! <br>default command";
    }
}
