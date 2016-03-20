<?php
namespace Squid\Base\CmdCreators;


/**
 * Data manipulation language command.
 */
interface IDml {
	
	/**
	 * Execute a dml command.
	 * @param bool $returnCount If true, return the nubmer of affected rows or 
	 * -1 for error; otheriwse return true for success or false for error.
	 * @return int|bool -1 on error, number of affected rows, or true/false 
	 * for succes/failer.
	 */
	public function executeDml($returnCount = false);
	
}