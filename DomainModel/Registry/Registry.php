<?php 
/**
 * Реестр
 * 
 * Этот шаблон предназначен для того, чтобы предоставлять доступ к объектам во всей системе, 
 * позволяет обойти каналы связи и изабвиться от длинных путей передачи информации.
 * Лучшая альтернатива глобальным переменным.
 * 
 * Является синглтоном.
 * (Камни не кидать)
 */
namespace Registry;

use Traits\SingletonTrait;

// use app\Conf\Conf;
// use app\ApplicationHelper\ApplicationHelper;
// use app\Requests\Request;

class Registry 
{
    use SingletonTrait;

    private $pdo;

    /**
     * Записать в реестр объект PDO
     * Для простоты, создаю здесь. В реальном проекте надо создавать классом инициализации системы
     */
    public function setPdo()
    {
        try {
            $pdo = new \PDO('mysql:host=localhost;dbname=domain-model', 'root', '');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e){
            echo "Соединение с БД не успешно: " . $e->getMessage() . "<br>";
            exit;
        }
        $this->pdo = $pdo;
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
