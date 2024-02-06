<?php

class stackClass
{
    private $data = [];

    public function add(string $item)
    {
        array_push($this->data, $item);
    }

    public function get(): string
    {
        return array_shift($this->data);
    }

    public function count()
    {
        return count($this->data);
    }

    public function clear()
    {
        $this->data = [];
    }
}

$stack = new stackClass();

$stack->add("Audi");
$stack->add("VW");
$stack->add("BMW");
$stack->add("123");
$stack->add("!!!");

echo "Перший елемент: " . $stack->get() . PHP_EOL;

echo "Кількість елементів: " . $stack->count() . PHP_EOL;

$stack->clear();
echo "Після очищення: " . $stack->count() . PHP_EOL;