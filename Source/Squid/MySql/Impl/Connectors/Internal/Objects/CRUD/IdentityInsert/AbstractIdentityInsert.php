<?php
namespace Squid\MySql\Impl\Connectors\Internal\Objects\CRUD\IdentityInsert;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Impl\Connectors\Objects\ObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;
use Squid\MySql\Connectors\Objects\CRUD\Generic\IObjectInsert;


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
			$this->insertObject = new ObjectConnector($this->ormConnector);
		}
		
		return $this->insertObject->insert($object, $ignore);
	}
}