<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


/**
 * Implementation for the IDml methods.
 * @method \PDOStatement execute()
 * @see \Squid\MySql\Command\IDml
 */
trait TDml
{
	/**
	 * @inheritdoc
	 */
	public function executeDml($returnCount = false) 
	{
		$result	= $this->execute();
		
		if (!$result) {
			return false;
		}
		
		return ($returnCount ? $result->rowCount() : true);
	}
}