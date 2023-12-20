<?php

function fibonacci($qnt) {
    $result = array(0,1);
    for( $i=0; $i < ($qnt-2); $i++ ){
        $operation = $result[$i] + $result[$i+1];
        array_push( $result, $operation );
    }
    return $result;
}

echo 'Числа Фібоначчі:' . PHP_EOL;
foreach(fibonacci(34) as $result) {
    echo $result . PHP_EOL;
}

echo PHP_EOL . 'Сума чісел Фібоначчі: ' . array_sum(fibonacci(34));