<?php
/**
 * Шаблон Identity Object дает программистам клиентского кода возможность определять критерии поиска без ссылки на запрос к базе данных. Он
 * также избавляет от необходимости создавать специальные методы запросов для
 * различных видов операций поиска, которые могут понадобиться пользователю.
 * Одна из целей шаблона Identity Object — оградить пользователей от подробностей реализации базы данных. 
 */
namespace IdentityObject;

class IdentityObject
{
    protected $currentfield = null;
    protected $fields       = [];
    private   $enforce      = [];

    // Объект идентичности может быть создан пустым
    // или же с отдельным полем
    public function __construct(string $field = null, array $enforce = null)
    {
        if (! is_null($enforce) ) {
            $this->enforce = $enforce;
        }

        if ( ! is_null($field) ) {
            $this->field($field);
        }
    }

    // Имена полей, на которые наложено данное ограничение
    public function getObjectFields()
    {
        return $this->enforce;
    }

    // Вводит новое поле.
    // Генерирует ошибку, если текущее поле неполное
    // (т.е. age, а не age > 40) .
    // Этот метод возвращает ссылку на текущий объект
    // и тем самым разрешает текучий синтаксис
    public function field(string $fieldname): self
    {
        if (! $this->isVoid() && $this->currentfield->isIncomplete()) {
            throw new \Exception ("IdentityObject(37): Неполное поле <br>");
        }

        $this->enforceField($fieldname);

        if (isset($this->fields[$fieldname])) {
            $this->currentfield=$this->fields[$fieldname];
        } else {
            $this->currentfield = new Field($fieldname);
            $this->fields[$fieldname]=$this->currentfield;
        }

        return $this;
    }

    // Имеются ли уже какие-нибудь поля
    // у объекта идентичности?
    public function isVoid(): bool
    {
        return empty($this->fields);
    }

    // Допустимо ли заданное имя поля?
    public function enforceField(string $fieldname)
    {
        if (! in_array($fieldname, $this->enforce) && ! empty($this->enforce) ) {
            $forcelist = implode(', ', $this->enforce);
            throw new \Exception("IdentityObject(64): {$fieldname} не является корректным полем ($forcelist) <br>");
        }
    }

    // Вводит операцию равенства в текущее поле,
    // например, значение 'age' превращается
    // в значение 'age=40'. Возвращает ссылку на
    // текущий объект через метод operator()
    public function eq($value): self
    {
        return $this->operator(" = ", $value);
    }

    // Операция сравнения "меньше"
    public function lt($value): self
    {
        return $this->operator("<", $value);
    }

    // Операция сравнения "больше"
    public function gt($value): self
    {
        return $this->operator(">", $value);
    }

    // Выполняет подготовку к операциям с полями.
    // Получает текущее поле и вводит операцию
    // и проверяемое значение
    private function operator(string $symbol, $value): self
    {
        if ($this->isVoid()) {
            throw new \Exception ("IdentityObject(95): Поле не определено <br>");
        }
        $this->currentfield->addTest($symbol, $value);
        return $this;
    }

    // Возвращает все полученные до сих пор результаты
    // сравнения из ассоциативного массива
    public function getComps(): array
    {
        $ret = [];

        foreach ($this->fields as $field) {
            $ret = array_merge($ret, $field->getComps());
        }

        return $ret;
    }
}
