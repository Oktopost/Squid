<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdUpsert;


class CmdUpsert extends CmdInsert implements ICmdUpsert 
{
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithSet;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT;
	
	/**
	 * @var int Index of the where clause.
	 */
	private static $PART_SET;
	
	
	/**
	 * Get the parts this query can have.
	 * @return array Array containing only the part as keys and values set to false.
	 */
	protected function getDefaultParts()
	{
		if (!isset(CmdUpsert::$DEFAULT))
		{
			CmdUpsert::$DEFAULT		= parent::getDefaultParts();
			CmdUpsert::$PART_SET	= count(CmdUpsert::$DEFAULT);
			
			CmdUpsert::$DEFAULT[CmdUpsert::$PART_SET] = false;
		}
		
		return CmdUpsert::$DEFAULT;
	}
	
	/**
	 * Combine all the parts into one sql.
	 * @return string Created query.
	 */
	protected function generate()
	{
		return
			parent::generate() .
			Assembly::append(
				'ON DUPLICATE KEY UPDATE',
				$this->getPart(CmdUpsert::$PART_SET),
				', ');
	}
	
	
	/**
	 * @inheritdoc
	 */
	public function setUseNewValues($fields) 
	{
		if (!is_array($fields)) $fields = [$fields];
		
		foreach ($fields as $field) 
		{
			$this->setExp("`$field`", "VALUES(`$field`)");
		}
		
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	public function setDuplicateKeys($fields) 
	{
		return $this->setUseNewValues(
			array_diff(
				$this->getFields(), 
				(is_array($fields) ? $fields : [$fields])
			));
	}
	
	/**
	 * @inheritdoc
	 */
	public function _set($exp, $bind = []) 
	{
		return $this->appendPart(CmdUpsert::$PART_SET, $exp, $bind); 
	}
}