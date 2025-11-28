<?php


namespace Ion\Task8227psr\Controller;

class ConvertToShort
{
	
	public $config;
	public $oauth_token;
	public $cloud_folders;
	
	function __construct()
	{
		$Config = new Config();
		$this->config = $Config->get_data();
		
		$this->oauth_token = $this->config['yandex']['oauth_token'];
		$this->cloud_folders = $this->config['yandex']['cloud_folders'];
		

	}

	public function do_it()
	{

		
		
		$YandexCloud = new YandexCloud();
		$oauthToken = $this->oauth_token;
		
		$arr_answer = $YandexCloud->getOAuthToken($oauthToken);
		
		$iamToken = $arr_answer['iamToken'];

		$folder = $YandexCloud->getCloudFolder($iamToken, $this->cloud_folders);
		
		$folderId = $folder['id'];
		
		$url = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';
		
		$json_data = '{
  "modelUri": "gpt://'.$folderId.'/yandexgpt/latest",
  "completionOptions": {
    "stream": false,
    "temperature": 0.6,
    "maxTokens": "2000",
    "reasoningOptions": {
      "mode": "DISABLED"
    }
  },
  "messages": [
    {
      "role": "system",
      "text": "мне надо переделать строки для объявлений в рекламе например: исходная строка: ПОДРАМНИК двигателя - -K5 2.0 строка для рекламы (для людей): Подрамник двигателя k5 теперь давай: исходная строка: РАСПРЕДВАЛ выпускной - -SOLARIS/RIO 17- 1.4 KAPPA сделай мне строку для рекламы"
    },
    {
      "role": "assistant",
      "text": "Распределительный вал для выпускного коллектора Solaris/Rio с двигателем 1,4 KAPPA — то, что нужно для бесперебойной работы вашего автомобиля!"
    },
    {
      "role": "user",
      "text": "нет надо проще: Распредвал выпускной SOLARIS"
    },
    {
      "role": "assistant",
      "text": "Выпускной распределительный вал для Solaris/Rio — идеальное решение для вашего двигателя 1.4 KAPPA."
    },
    {
      "role": "user",
      "text": "нет ты пойми меня проще, значит именно так надо, как я сказал: Распредвал выпускной SOLARIS"
    },
    {
      "role": "assistant",
      "text": "Распредвал выпускной для Solaris — надёжный и качественный."
    },
    {
      "role": "user",
      "text": "максимальная длина ответа должна быть 38 символов"
    },
    {
      "role": "assistant",
      "text": "Распредвал выпускной для SOLARIS/RIO."
    },
    {
      "role": "user",
      "text": "хорошо, теперь я даю тебе 5 следующих: УКАЗАТЕЛЬ поворота в бампере RH, ФОНАРЬ задний правый (в крыло) - -OPTIMA, ФОНАРЬ задний правый (в крышку багажника) (евро)  - - OPTIMA, РАДИАТОР охлаждения HAVAL H9 бензин, РАДИАТОР охлаждения HAVAL H9 бензин"
    },
    {
      "role": "assistant",
      "text": "Указатель поворота в бампере RH.\nФонарь задний правый для KIA OPTIMA (в крыло).\nЗадний правый фонарь евро для KIA OPTIMA.\nРадиатор охлаждения для HAVAL H9 бензин.\nРадиатор охлаждения для HAVAL H9 бензин."
    },
    {
      "role": "user",
      "text": "максимальная длина ответа должна быть 38 символов и сама суть, чтоб человек понял"
    },
    {
	  "role": "assistant",
	  "text": "Указатель поворота в бампер RH.\nЗадний правый фонарь для KIA OPTIMA.\nЕвро-фонарь задний правый для OPTIMA. \nРадиатор охлаждения для HAVAL H9.\nРадиатор охлаждения для HAVAL H9."
	},
    {
      "role": "user",
      "text": "хорошо, делай так"
    }
  ]
}';
		
		
		$post_data = $json_data;
		
		$post = true;
		
		$headers = array(
				
				"Content-Type: application/json",
				'Authorization: Bearer ' . $iamToken
		);
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if ($post !== false)
		{
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$response = curl_exec($ch);
		
		$date = '';
		
		$file_data = realpath(__DIR__ . '/../../data/') . '/' . 'answer_' . $date . '.json';
		//$fileResult = realpath(__DIR__) . "/data/answer7.json";
		file_put_contents($file_data, $response);
		
		
		$a = 1;
		
	}
}
