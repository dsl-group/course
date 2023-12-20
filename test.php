<?php
// phpinfo();

class First {

    function ViewType($type) {
        $result = array(
            '1' => 'animal',
            '2' => 'people',
            '3' => 'robot'
        );
        return $result[$type];
    }

    function Animal($type) {
        $result  = array(
            '0' => 'Лев',
            '1' => 'Мамонт',
            '2' => 'Мавпа',
            '3' => 'Акула'
        );
        return $result[$type];
    }

}

class Second extends First {

    function Product($type) {
        $result  = array(
            '0' => 'Полуниця',
            '1' => 'Горобина',
            '2' => 'Сливи'
        );
        return $result[$type];
    }

}

$first = new First;
$second = new Second;

echo $first->ViewType(1) . PHP_EOL;
echo $second->Animal(1) . PHP_EOL;
echo $second->Product(1) . PHP_EOL;