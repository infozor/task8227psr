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
		
		
		$Dialogues = new Dialogues();
		$Dialog = $Dialogues->get_data('dialog1.json');
		
		$messages = json_encode($Dialog['messages']);
		
		
		
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
   "messages":'.$messages.'
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
