<?php 
namespace app\Commands\Http;

use app\Commands\Command;
use app\Requests\Request;

class HttpExempleCommand extends Command
{
    public function execute(Request $request)
    {
        return false;
    }
}
