<?php

function showArray($array) {
    echo implode(',', $array) . PHP_EOL;
}

function sortUsingBubble($array, $n) {
    if ($n == 1) {
        return $array;
    }

    for ($i = 0; $i < $n - 1; $i++) {
        if ($array[$i] > $array[$i + 1]) {
            list($array[$i], $array[$i + 1]) = array($array[$i + 1], $array[$i]);
            showArray($array);
        }
    }

    return sortUsingBubble($array, $n - 1);
}

$array = [10, 7, 1, 4, 5, 9, 56, 51, 15, 96];

$result = sortUsingBubble($array, count($array));

//-- Результат --//
// 7,10,1,4,5,9,56,51,15,96
// 7,1,10,4,5,9,56,51,15,96
// 7,1,4,10,5,9,56,51,15,96
// 7,1,4,5,10,9,56,51,15,96
// 7,1,4,5,9,10,56,51,15,96
// 7,1,4,5,9,10,51,56,15,96
// 7,1,4,5,9,10,51,15,56,96
// 1,7,4,5,9,10,51,15,56,96
// 1,4,7,5,9,10,51,15,56,96
// 1,4,5,7,9,10,51,15,56,96
// 1,4,5,7,9,10,15,51,56,96

//-- Пошук найменшого числа --//
// Простіше за все після сортування вивести перше число з масиву,
// але якщо обробляти початковий массив можна зробити порівняння так

$minNumber = $array[0];

foreach ($array as $number) {
    if ($number < $minNumber) {
        $minNumber = $number;
    }
}

echo "Найменше число: " . $minNumber . "\n";