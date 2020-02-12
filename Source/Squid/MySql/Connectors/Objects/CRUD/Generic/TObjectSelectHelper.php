<?php
namespace Squid\MySql\Connectors\Objects\CRUD\Generic;


/**
 * @mixin IObjectSelect 
 */
trait TObjectSelectHelper
{
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectObjectByField(string $field, $value)
	{
		return $this->selectObjectByFields([$field => $value]);
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByField(string $field, $value)
	{
		return $this->selectFirstObjectByFields([$field => $value]);
	}
}