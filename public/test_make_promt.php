<?php


//require_once dirname(__DIR__) . '/vendor/autoload.php';

$path = realpath(__DIR__ . '/../vendor/');
require $path . '/' . 'autoload.php';

use Ion\Task8227psr\Controller\ConvertToShort;


$ConvertToShort = new ConvertToShort();

$params['yadv_packet_id'] = 1;
$fetch = $ConvertToShort->get_packet($params);



$pre_text = 'хорошо, теперь я даю тебе';
$piece_text = count($fetch).' следующих: ';

$str = $pre_text.' '. $piece_text;

//5 следующих: УКАЗАТЕЛЬ поворота в бампере RH

for ($i=0;$i<count($fetch);$i++)
{
	$str .= 'id:'.$fetch[$i]['id'].' '.'name:'.' '.$fetch[$i]['name'].',';
}

$json =
'{
	"role": "user",
	"text": "'.$str.'"
}';



//$date = ''; 
$date = date('dmy_His');
$file_data = realpath(__DIR__ . '/../promts/') . '/' . 'promt_' . $date . '.json';
file_put_contents($file_data, $json, FILE_APPEND);



$a = 1;