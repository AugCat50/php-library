<?php
/**
 * Хранилище данных, которые требуется передать на уровень представления
 */
namespace app\Responce;

class Responce
{
    /**
     * Массив, содержащий feedback на запрос
     * 
     * @var array
     */
    protected $feedback = [];

    public function __construct($feedback){
        if(is_array($feedback)){
            $this->feedback = $feedback;
        } else {
            $this->feedback[] = $feedback;
        }
    }

    /**
     * Установить Feedback на запрос. Есть смысл перенести в отдельный класс Response
     * 
     * @param string $msg
     * 
     * @return void
     */
    public function addFeedback(string $msg)
    {
        array_push($this->feedback, $msg);
    }

    /**
     * Получить Feedback на запрос. Есть смысл перенести в отдельный класс Response
     * 
     * @return array
     */
    public function getFeedback(): array
    {
        return $this->feedback;
    }

    /**
     * Получить Feedback на запрос в виде строки. Есть смысл перенести в отдельный класс Response
     * 
     * @return string
     */
    public function getFeedbackString($separator = "\n"): string
    {
        return implode($separator, $this->feedback);
    }

    /**
     * Очистить массив feedback
     * 
     * @return void
     */
    public function clearFeedback()
    {
        $this->feedback = [];
    }
}
