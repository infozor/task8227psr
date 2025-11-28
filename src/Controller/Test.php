<?php
declare(strict_types = 1)
	;

namespace Ion\Task8227psr\Controller;

class Test
{
	/**
	 * Функция сложения двух чисел
	 *
	 * @param int $a
	 *        	Первое число
	 * @param int $b
	 *        	Второе число
	 * @return int Результат сложения
	 */
	public function add(int $a, int $b): int
	{
		return $a + $b;
	}
}