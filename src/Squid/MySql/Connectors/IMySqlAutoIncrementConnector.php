<?php
namespace Squid\MySql\Connectors;


use Objection\LiteObject;


interface IMySqlAutoIncrementConnector extends IMySqlObjectConnector
{
	/**
	 * @param string $field
	 * @return static
	 */
	public function setIdField($field = 'Id');
	
	/**
	 * @param LiteObject $object
	 * @return bool
	 */
	public function save(LiteObject $object);
	
	/**
	 * @param LiteObject $object
	 * @return bool
	 */
	public function update(LiteObject $object);
	
	/**
	 * @param int $id
	 * @return LiteObject|null
	 */
	public function load($id);
	
	/**
	 * @param int $id
	 * @return bool
	 */
	public function delete($id);
}