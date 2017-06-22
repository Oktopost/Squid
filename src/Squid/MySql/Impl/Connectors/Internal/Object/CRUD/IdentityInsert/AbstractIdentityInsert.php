<?php
namespace Squid\MySql\Impl\Connectors\Internal\Object\CRUD\IdentityInsert;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Impl\Connectors\Object\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;
use Squid\MySql\Connectors\Object\CRUD\IObjectInsert;


abstract class AbstractIdentityInsert implements IObjectInsert
{
	/** @var  IObjectInsert */
	private $insertObject;
	
	/** @var AbstractORMConnector */
	private $ormConnector;
	
	
	/**
	 * @return AbstractORMConnector
	 */
	protected function getORMConnector(): AbstractORMConnector
	{
		return $this->ormConnector;
	}
	
	/**
	 * @return IMySqlConnector
	 */
	protected function getConnector(): IMySqlConnector
	{
		return $this->ormConnector->getConnector();
	}
	
	
	/**
	 * @param AbstractORMConnector $connector
	 */
	public function __construct(AbstractORMConnector $connector)
	{
		$this->ormConnector = $connector;
	}
	
	
	/**
	 * @param $object
	 * @param bool $ignore
	 * @return int|false
	 */
	protected function doInsert($object, bool $ignore = false)
	{
		if (!$this->insertObject)
		{
			$this->insertObject = new GenericObjectConnector($this->ormConnector);
		}
		
		return $this->insertObject->insert($object, $ignore);
	}
}