<?php
/**
 * Оболочка для массива данных требуемых в системе.
 * Применяется для хранения переменных окружения и роутов.
 * 
 * В классе Conf определены лишь методы get () и set (), 
 * хотя в более развитых классах может быть организован поиск и синтаксический анализ файлов, а также обнаружение данных.
 */
namespace app\Conf;

class Conf
{
    /**
     * @var array
     */
    private $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * Установить значение в массив
     * 
     * @return void
     */
    public function set(string $key, $val)
    {
        $this->data[$key] = $val;
    }
    
    /**
     * Получить значение из массива. null , если не найдено.
     * 
     * @return mixed|null
     */
    public function get(string $key)
    {
        $j = $this->data[$key];
        if (isset($j)) {
            return $j;
        }
        return null;
    }
}
