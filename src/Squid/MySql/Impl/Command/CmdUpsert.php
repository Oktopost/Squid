<?php
namespace Squid\MySql\Impl\Command;


use Squid\Common;
use Squid\MySql\Command\ICmdUpsert;


class CmdUpsert extends CmdInsert implements ICmdUpsert {
	use Squid\MySql\Traits\CmdTraits\TWithSet;
	
	
	/**
	 * @var int Index of the where clause.
	 */
	public static $PART_SET;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT;
	
	
	/**
	 * Use the new values of the fields that have duplicate error.
	 * This function fill generate: fieldA = VALUES(fieldA) sub queries for all
	 * the fields spesified in the array, or all the insert fields minus $fields 
	 * if $negate is true (default behavior).
	 * @param string|array $fields single field, or array of fields that should 
	 * be ignored or used (depending on the value if $negate) to set them to the new 
	 * values used in insert.
	 */
	public function setUseNewValues($fields) {
		Common::toArray($fields);
		
		foreach ($fields as $field) {
			$this->setExp("`$field`", "VALUES(`$field`)");
		}
		
		return $this;
	}
	
	/**
	 * List of fields that are the keys of this insert and on duplicate, all fields
	 * but this keys should be copied. This is as logical inversion to setUseNewValues(...)
	 * @param string|array $fields single field, or array of fields that are a part of a 
	 * unique/primary key on the table. On Duplicate all fields but thouse are used in the set cluster.
	 * @return ICmdUpsert
	 */
	public function setDuplicateKeys($fields) {
		return $this->setUseNewValues(array_diff($this->getFields(), Common::toArray($fields)));
	}
	
	/**
	 * Function called by TWithSet.
	 * @param string $exp Full set expression.
	 * @param mixed $bind Bind params, if any.
	 * @return mixed Always returns self.
	 */
	public function _set($exp, $bind = false) {
		return $this->appendPart(CmdUpsert::$PART_SET, $exp, $bind); 
	}
	
	
	/**
	 * Get the parts this query can have.
	 * @return array Array contianing only the part as keys and values set to false.
	 */
	protected function getDefaultParts() {
		if (!isset(CmdUpsert::$DEFAULT)) {
			CmdUpsert::$DEFAULT		= parent::getDefaultParts();
			CmdUpsert::$PART_SET	= count(CmdUpsert::$DEFAULT);
			
			CmdUpsert::$DEFAULT[CmdUpsert::$PART_SET] = false;
		}
		
		return CmdUpsert::$DEFAULT; 
	}
	
	/**
	 * Commbine all the parts into one sql.
	 * @return string Created query.
	 */
	protected function generate() {
		return 
			parent::generate() . 
			Assembly::append(
				'ON DUPLICATE KEY UPDATE', 
				$this->getPart(CmdUpsert::$PART_SET), 
				', ');
	}
}