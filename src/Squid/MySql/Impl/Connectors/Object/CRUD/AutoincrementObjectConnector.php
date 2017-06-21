<?php
namespace Squid\MySql\Impl\Connectors\Object\CRUD;


use Squid\MySql\Impl\Connectors\Object\CRUD\Insert\AIObjectInsert;

class AutoincrementObjectConnector extends AbstractIdentityObjectConnector
{
	/** @var AIObjectInsert */
	private $aiObjectInsert;
	
	
	private function getObjectInsert(): AIObjectInsert
	{
		if (!$this->aiObjectInsert)
		{
			$this->aiObjectInsert = new AIObjectInsert($this);
			$this->aiObjectInsert->setAIProperty($this->getIDProperty());
		}
		
		return $this->aiObjectInsert;
	}
	
	
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false)
	{
		return $this->getObjectInsert()->insert($object, $ignore);
	}
}