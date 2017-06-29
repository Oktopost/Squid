<?php
namespace Squid\MySql\Connectors\Object\Join\OneToOne;


interface IOneToOneConfig
{
	/**
	 * @param mixed|array $parents
	 * @return array|null A byFields expression that can be used against the children's Connector 
	 */
	public function getWhereForChildren($parents): ?array;
	
	
	/**
	 * @param mixed|array $parents
	 * @param mixed|array $children
	 */
	public function combine($parents, $children): void;

	/**
	 * Called after any update, upsert or insert operation in the parent object. 
	 * @param mixed|array $parents
	 * @return array|null Array of modified children.
	 */
	public function afterParentSaved($parents): ?array;
}