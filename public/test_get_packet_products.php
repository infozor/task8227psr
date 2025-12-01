<?php


//require_once dirname(__DIR__) . '/vendor/autoload.php';

$path = realpath(__DIR__ . '/../vendor/');
require $path . '/' . 'autoload.php';

use Ion\Task8227psr\Controller\ConvertToShort;


$ConvertToShort = new ConvertToShort();

$params['yadv_packet_id'] = 1;
$result = $ConvertToShort->get_packet($params);



$a = 1;