<?php

namespace Ion\Task8227psr\Controller;

class Dialogues
{
	
	public $file_path;
	
	function __construct()
	{
		$this->file_path = realpath(__DIR__ . '/../../dialogues');
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
}
