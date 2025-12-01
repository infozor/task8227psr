<?php

namespace Ion\Task8227psr\Controller;

class Dialogues
{
	
	private $file_path;
	private $file_path_promts;
	
	function __construct()
	{
		$this->file_path = realpath(__DIR__ . '/../../dialogues');
		$this->file_path_promts = realpath(__DIR__ . '/../../promts');
	}
	function get_data($file)
	{
		$dataDefinition = null;
		
		try
		{
			if (!file_exists($this->file_path.'/'. $file))
			{
				throw new \Exception('config файл '.$this->file_path.'/'.$file.' не найден.');
			}

			// Читаем JSON и декодируем в массив
			$Data = file_get_contents($this->file_path.'/'.$file);
			
			$dataDefinition = json_decode($Data, true);
		}
		catch ( \Exception $e )
		{
			echo "Ошибка: " . $e->getMessage() . "\n";
		}
		return $dataDefinition;
	}
	
	function get_promt($file)
	{
		$dataDefinition = null;
		
		$file_path = $this->file_path_promts;
		
		try
		{
			if (!file_exists($file_path.'/'. $file))
			{
				throw new \Exception('config файл '.$file_path.'/'.$file.' не найден.');
			}
			
			// Читаем JSON и декодируем в массив
			$Data = file_get_contents($file_path.'/'.$file);
			
			$dataDefinition = json_decode($Data, true);
		}
		catch ( \Exception $e )
		{
			echo "Ошибка: " . $e->getMessage() . "\n";
		}
		return $dataDefinition;
	}
}
