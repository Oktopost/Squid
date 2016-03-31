<?php
namespace Squid\MySql\Traits\CmdTraits;


/**
 * Implementation for the IDml methods.
 * This trait uses the execute() command.
 */
trait TDml {
	
	/**
	 * @param bool $returnCount
	 * @return int|bool Number of affected rows if $returnCount is true.
	 */
	public function executeDml($returnCount = false) {
		$result	= $this->execute();
		
		if (!$result) {
			return false;
		}
		
		return ($returnCount ? $result->rowCount() : true);
	}
}