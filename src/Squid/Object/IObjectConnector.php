<?php
namespace Squid\Object;


use Objection\LiteObject;
use Squid\MySql\IMySqlConnector;


interface IObjectConnector
{
	/**
	 * @param string $className
	 * @return static
	 */
	public function setDomain($className);
	
	/**
	 * @param LiteObject $object
	 * @return int|bool
	 */
	public function insert(LiteObject $object);
	
	/**
	 * @param LiteObject $object
	 * @return int|bool
	 */
	public function loadOne(array $byFields, array $orderFields = []);
}