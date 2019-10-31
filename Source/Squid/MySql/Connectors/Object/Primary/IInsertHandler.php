<?php
namespace Squid\MySql\Connectors\Object\Primary;


use Squid\MySql\Connectors\Object\CRUD\Identity\IIdentityInsert;

interface IInsertHandler
{
	/**
	 * @param callable|IIdentityInsert $insert
	 * @return static|IInsertHandler
	 */
	public function setInsertProvider($insert): IInsertHandler;

	/**
	 * @param string $field
	 * @return static|IInsertHandler
	 */
	public function setIdProperty(string $field): IInsertHandler;
	
	/**
	 * @param array $items
	 * @return int|false
	 */
	public function insert(array $items);
}