<?php 
namespace DomainObjectAssembler\IdentityObject;

class Field
{
    protected $name       = null;
    protected $operator   = null;
    protected $comps      = [];
    protected $incomplete = false;

    // Задать имя поля (например, age)
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    // Ввести операцию и значение для тестирования
    // (например, больше 40), а также свойство $comps
    public function addTest(string $operator, $value)
    {
        $this->comps[] = [
            'name'     => $this->name,
            'operator' => $operator,
            'value'    => $value
        ];
    }

    // $comps - это массив, поэтому проверить одно поле
    // можно не одним, а несколькими способами
    public function getComps(): array
    {
        return $this->comps;
    }

    // Если массив $comps не содержит элементы,
    // это означает, что данные сравнения с полем и
    // само поле не готовы для применения в запросе
    public function isIncomplete(): bool
    {
        return empty($this->comps);
    }
}
