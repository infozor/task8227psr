<?php

namespace Ion\Task8227psr\Controller;

class YandexCloud
{
	function getOAuthToken($oauthToken)
	{
		// URL для запроса токена
		$url = 'https://iam.api.cloud.yandex.net/iam/v1/tokens';
		
		// Данные, которые мы будем отправлять
		$data = json_encode([
				'yandexPassportOauthToken' => $oauthToken
		]);
		
		// Инициализация cURL
		$ch = curl_init($url);
		
		// Установка параметров cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data)
		]);
		
		// Выполнение запроса
		$response = curl_exec($ch);
		
		// Проверка на ошибки
		if (curl_errno($ch))
		{
			echo 'Ошибка запроса: ' . curl_error($ch);
			return null;
		}
		
		// Закрытие cURL
		curl_close($ch);
		
		// Обработка ответа
		$data = json_decode($response, true);
		
		return $data;
	}
	
	function getCloudFolder($oauthToken, $folder_id)
	{
		$url = 'https://resource-manager.api.cloud.yandex.net/resource-manager/v1/folders/' . $folder_id; // URL API
		
		// Инициализация cURL
		$ch = curl_init($url);
		
		// Установка параметров cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Authorization: Bearer ' . $oauthToken,
				'Content-Type: application/json'
		]);
		
		// Выполнение запроса
		$response = curl_exec($ch);
		
		// Проверка на ошибки
		if (curl_errno($ch))
		{
			echo 'Ошибка запроса: ' . curl_error($ch);
			return;
		}
		
		// Закрытие cURL
		curl_close($ch);
		
		// Обработка ответа
		$data = json_decode($response, true);
		
		
		
		return $data;
	}
}