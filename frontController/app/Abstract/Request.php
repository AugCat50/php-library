<?php 
/**
 * Абстрактный класс запроса. Реализации находятся в соответствующих папках Http и Cli, в зависимости от типа запроса
 */
namespace app\Abstract;

abstract class Request
{
    public function e($q)
    {
        echo 'Hello<br>';
        echo $q;
    }
}
