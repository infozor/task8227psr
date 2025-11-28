<?php

namespace Ion\Task8227psr\Controller;

class Config
{
	
	public $file_config;
	
	function __construct()
	{
		$this->file_config = realpath(__DIR__ . '/../../config/').'/config.json';
	}
	function get_data()
	{
		try
		{
			if (!file_exists($this->file_config))
			{
				throw new \Exception("config файл '$this->file_config' не найден.");
			}

			// Читаем JSON и декодируем в массив
			$configData = file_get_contents($this->file_config);
			
			$configDefinition = json_decode($configData, true);
		}
		catch ( \Exception $e )
		{
			echo "Ошибка: " . $e->getMessage() . "\n";
		}
		return $configDefinition;
	}
}
