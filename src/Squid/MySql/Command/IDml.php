<?php
namespace Squid\MySql\Command;


interface IDml
{
	/**
	 * @param bool $returnCount If true, return the number of affected.
	 * @return int|bool Number of affected rows, or true/false
	 * for success/failure
	 */
	public function executeDml($returnCount = false);
}