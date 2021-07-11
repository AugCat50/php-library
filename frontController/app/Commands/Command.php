<?php
/**
 * 
 * Зависимости запрос - комманда обозваны в системе роутами
 */
namespace app\Commands;

use app\Requests\Request;

abstract class Command
{
    // abstract public function execute(CommandContext $context): bool;
    abstract public function execute(Request $request);
}
