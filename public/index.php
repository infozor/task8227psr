<?php

//require_once dirname(__DIR__) . '/vendor/autoload.php';

$path = realpath(__DIR__ . '/../vendor/');
require $path . '/' . 'autoload.php';

use Ion\Task8227psr\Controller\Test;
//use Ion\Task8227psr\Controller\Test;

$test = new Test();

// Тестируем простое сложение
$result1 = $test->add(5.1, 3);
echo "5 + 3 = " . $result1 . PHP_EOL;
