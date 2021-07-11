<?php 
/**
 * Абстрактный класс запроса. Реализации находятся в соответствующих папках Http и Cli, в зависимости от типа запроса.
 * 
 * Централизация операции обработки запросов в одном месте.
 * Объект типа Request может также служить удобным хранилищем данных, которые требуется передать на уровень представления, 
 * хотя для этого обычно применяется класс Response
 */
namespace app\Requests;

abstract class Request
{
    /**
     * Это простой канал связи, по которому классы контроллеров могут передавать сообщения пользователю. 
     */
    //TODO: Надо сделать фильтр ошибок или добавить отдельное свойство
    protected $properties;
    
    protected $feedback = [];
    protected $path     = '/';

    public function __construct()
    {
        $this->init();
    }

    /**
     * Метод init () отвечает за наполнение закрытого массива $properties для обработки в дочерних классах
     */
    abstract public function init();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
    * Получение URL запроса, для нахождения соответствующей команды в роутах
    */
    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Как только будет получен объект типа Request, вы должны обращаться к параметрам HTTP-запроса исключительно с помощью вызова метода 
     * getProperty (). Этому методу передается ключ в виде символьной строки, а 
     * он возвращает соответствующее значение, хранящееся в массиве $properties
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function getProperty(string $key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }

        return null;
    }

    /**
     * Можно ввести дополнительные данные к данным запроса
     */
    public function setProperty(string $key, $val)
    {
        $this->properties[$key] = $val;
    }

    public function addFeedback(string $msg)
    {
        array_push($this->feedback, $msg);
    }

    public function getFeedback(): array
    {
        return $this->feedback;
    }

    public function getFeedbackString($separator = "\n"): string
    {
        return implode($separator, $this->feedback);
    }

    public function clearFeedback()
    {
        $this->feedback = [];
    }
}
