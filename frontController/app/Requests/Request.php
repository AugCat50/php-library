<?php 
/**
 * Абстрактный класс запроса. Реализации находятся в соответствующих папках Http и Cli, в зависимости от типа запроса
 */
namespace app\Requests;

abstract class Request
{
    public function e($q)
    {
        echo 'Hello<br>';
        echo $q;
    }
}
