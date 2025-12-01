<?php

//require_once dirname(__DIR__) . '/vendor/autoload.php';

$path = realpath(__DIR__ . '/../vendor/');
require $path . '/' . 'autoload.php';

use Ion\Task8227psr\Controller\ConvertToShort;


$ConvertToShort = new ConvertToShort();


$result1 = $ConvertToShort->do_it();

$a = 1;
