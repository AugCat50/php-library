<?php 
/**
 * Класс запроса для Http
 */
namespace app\Requests\Http;

use app\Requests\Request;

class HttpRequest extends Request
{
    private $method;
    public $path;

    public function init()
    {
        // Ради удобства здесь игнорируются отличия
        // в методах запросов POST, GET, т.д., но
        // этого нельзя делать в реальном проекте!
        $this->properties = $_REQUEST;
        // d($this->properties);

        if (isset($_SERVER['PATH_INFO'])) {
            $this->path = $_SERVER['PATH_INFO'];
        } else{
            $this->path = '/';
        }

        $this->method     = $_SERVER['REQUEST_METHOD'];
        // d($_SERVER);
        
        $this->path       = (empty ($this->path) ) ? : $this->path;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
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
}
