<?php
namespace Squid\Base\Cmd;


/**
 * Data Manipulation Language command.
 */
interface IDml
{
	/**
	 * @param bool $returnCount If true, return the number of affected.
	 * @return int|bool Number of affected rows, or true/false
	 * for success/failure
	 */
	public function executeDml($returnCount = false);
}