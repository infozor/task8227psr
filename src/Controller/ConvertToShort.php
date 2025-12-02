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
		$Dialog = $Dialogues->get_data('dialog3.json');

		$Promt = $Dialogues->get_promt('promt3.json');

		array_push($Dialog['messages'], $Promt);

		$messages = json_encode($Dialog['messages']);

		$json_data = '{
  "modelUri": "gpt://' . $folderId . '/yandexgpt/latest",
  "completionOptions": {
    "stream": false,
    "temperature": 0.6,
    "maxTokens": "2000",
    "reasoningOptions": {
      "mode": "DISABLED"
    }
  },
   "messages":' . $messages . '
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

	/**
	 *
	 * @author Ionov AV
	 * @дата:    01.12.2025
	 * @время: 16:59
	 * Описание функции
	 * надо взять список продуктов пакета чтобы потом дополнить shorts
	 */
	public function get_packet($params)
	{
		$Db = new Db();

		// @set params
		//$params['id'] = 'id';

		//$params['yadv_packet_id'] = 1;
		/*
		 $params['good_id'] = 'good_id';
		 $params['name'] = 'name';
		 $params['chort_name'] = 'chort_name';
		 $params['created_at'] = 'created_at';
		 $params['updated_at'] = 'updated_at';
		 */

		$rows = $Db->get_yadv_a_packet_products($params);

		return $rows;
		//$a = 1;
	}
	/**
	 * @author Ionov AV
	 * @дата:    02.12.2025
	 * @время: 11:33 
	 * Описание функции
	 *
	 * Подготавливает данные для сохранения в базу данных из JSON файла
	 *
	 * Функция выполняет следующие действия:
	 * 1. Читает JSON файл с результатами ответа
	 * 2. Извлекает текстовые данные из структуры ответа
	 * 3. Разбирает текст на элементы формата "id:X short_name: Название"
	 * 4. Валидирует и форматирует данные
	 * 5. Возвращает структурированный результат для последующего сохранения в БД
	 *
	 * @return array Результат подготовки данных со следующей структурой:
	 *               [
	 *                   'success'    => bool,   // Статус выполнения операции
	 *                   'data'       => array,  // Массив разобранных данных
	 *                   'count'      => int,    // Количество разобранных элементов
	 *                   'message'    => string, // Сообщение о результате
	 *                   'error_type' => string  // Тип ошибки (только при success=false)
	 *               ]
	 *               Структура каждого элемента в 'data':
	 *               [
	 *                   'id'         => int,    // Идентификатор элемента
	 *                   'short_name' => string  // Краткое наименование
	 *               ]
	 *
	 * @throws RuntimeException Если файл не найден или содержит некорректный JSON
	 * @throws InvalidArgumentException Если текст имеет неверный формат
	 *
	 * @example
	 *   $result = $this->prepare_short_to_db();
	 *   if ($result['success']) {
	 *       foreach ($result['data'] as $item) {
	 *           $this->saveToDatabase($item['id'], $item['short_name']);
	 *       }
	 *   }
	 *
	 * @uses parseTextToArray() Для разбора текстовых данных
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @author [Ваше имя или название команды]
	 * @copyright [Название компании] [Год]
	 */
	public function prepare_short_to_db(): array
	{
		$date = '';
		$file_data = realpath(__DIR__ . '/../../data/') . '/' . 'answer_' . $date . '.json';

		$json = file_get_contents($file_data);
		$array = json_decode($json, true);
		$text = $array['result']['alternatives'][0]['message']['text'];

		try
		{
			$parsedData = $this->parseTextToArray($text);
			return [
					'success' => true,
					'data' => $parsedData,
					'count' => count($parsedData),
					'message' => 'Данные успешно разобраны'
			];
		}
		catch ( \InvalidArgumentException $e )
		{
			return [
					'success' => false,
					'data' => [],
					'count' => 0,
					'error_type' => 'validation',
					'message' => 'Ошибка валидации: ' . $e->getMessage()
			];
		}
		catch ( \RuntimeException $e )
		{
			return [
					'success' => false,
					'data' => [],
					'count' => 0,
					'error_type' => 'runtime',
					'message' => 'Ошибка выполнения: ' . $e->getMessage()
			];
		}
		catch ( \Exception $e )
		{
			return [
					'success' => false,
					'data' => [],
					'count' => 0,
					'error_type' => 'general',
					'message' => 'Общая ошибка: ' . $e->getMessage()
			];
		}
	}

	/**
	 * Разбирает текст с id и short_name в массив
	 *
	 * @param string $text
	 *        	Входной текст для разбора
	 * @return array Массив с данными
	 * @throws \InvalidArgumentException Если текст пустой или имеет неверный формат
	 *         https://chat.deepseek.com/a/chat/s/d303461c-d541-47b6-af07-2f84387aaa88
	 */
	private function parseTextToArray(string $text): array
	{
		if (empty(trim($text)))
		{
			throw new \InvalidArgumentException('Входной текст не может быть пустым');
		}

		$lines = explode("\n", $text);
		$result = [];

		foreach ( $lines as $index => $line )
		{
			$line = trim($line);

			if (empty($line))
			{
				continue;
			}

			if (!preg_match('/^id:(\d+)\s+short_name:\s*(.+)$/', $line, $matches))
			{
				throw new \InvalidArgumentException(sprintf('Неверный формат строки %d: "%s"', $index + 1, $line));
			}

			$id = ( int ) $matches[1];
			$shortName = trim($matches[2]);

			if ($id <= 0)
			{
				throw new \InvalidArgumentException(sprintf('Неверный ID в строке %d: %d', $index + 1, $id));
			}

			if (empty($shortName))
			{
				throw new \InvalidArgumentException(sprintf('Пустой short_name в строке %d', $index + 1));
			}

			$result[] = [
					'id' => $id,
					'short_name' => $shortName
			];
		}

		if (empty($result))
		{
			throw new \InvalidArgumentException('Не удалось извлечь данные из текста');
		}

		return $result;
	}
}
