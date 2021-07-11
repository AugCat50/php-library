<?php 
namespace app\Commands\Cli;

use app\Commands\Command;
use app\Requests\Request;

class CliExempleCommand extends Command
{
    public function execute(Request $request)
    {
        return false;
    }
}
