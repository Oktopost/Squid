<?php
namespace Squid\MySql\Impl\Connectors\Internal\Objects\CRUD\IdentityInsert;


use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;
use Squid\MySql\Connectors\Objects\CRUD\Generic\IObjectInsert;
use Squid\MySql\Impl\Connectors\Objects\Generic\GenericObjectConnector;


/**
 * Use if the ID field is already defined for any inserted object.
 */
class SimpleInsert implements IObjectInsert
{
	/** @var AbstractORMConnector */
	private $ormConnector;
	
	/** @var GenericObjectConnector */
	private $insertObject;
	
	
	/**
	 * @param AbstractORMConnector $connector
	 */
	public function __construct(AbstractORMConnector $connector)
	{
		$this->ormConnector = $connector;
	}
	
	
	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		if (!$this->insertObject)
			$this->insertObject = new GenericObjectConnector($this->ormConnector);
		
		return $this->insertObject->insertObjects($objects, $ignore);
	}
}