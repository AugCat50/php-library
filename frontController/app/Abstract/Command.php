<?php
/**
 * 
 * Зависимости запрос - комманда обозваны в системе роутами
 */
namespace app\Abstract;

abstract class Command
{
    abstract public function execute(CommandContext $context): bool;
}
