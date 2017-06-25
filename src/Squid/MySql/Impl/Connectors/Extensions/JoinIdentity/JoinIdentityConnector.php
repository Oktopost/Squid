<?php
namespace Squid\MySql\Impl\Connectors\Extensions\JoinIdentity;


use Squid\MySql\Connectors\Extensions\JoinIdentity\IJoinIdentityConfig;
use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;


class JoinIdentityConnector implements IIdentifiedObjectConnector
{
	/** @var IIdentifiedObjectConnector */
	private $objectConnector;
	
	/** @var IIdentifiedObjectConnector */
	private $dataConnector;
	
	/** @var IJoinIdentityConfig */
	private $config;
	
	
	public function setObjectConnector(IIdentifiedObjectConnector $connector): JoinIdentityConnector
	{
		$this->objectConnector = $connector;
		return $this;
	}
	
	public function setDataConnector(IIdentifiedObjectConnector $connector): JoinIdentityConnector
	{
		$this->dataConnector = $connector;
		return $this;
	}
	
	public function setConfig(IJoinIdentityConfig $config): JoinIdentityConnector
	{
		$this->config = $config;
		return $this;
	}
	

	/**
	 * @param mixed|array $id
	 * @return mixed|null|false
	 */
	public function load($id)
	{
		
	}

	/**
	 * @param mixed $id
	 * @return mixed|false
	 */
	public function deleteById($id)
	{
		if ($this->config->onDeleteObjectCascadeData())
		{
			$obj = $this->load($id);
			return ($obj ? $this->delete($obj) : $obj === null);
		}
		
		return $this->objectConnector->deleteById($id);
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		$res = $this->objectConnector->delete($object);
		
		if ($res !== false)
		{
			
		}
		
		return false;
	}

	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false)
	{
		// TODO: Implement insert() method.
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function save($object)
	{
		// TODO: Implement save() method.
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		// TODO: Implement update() method.
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		// TODO: Implement upsert() method.
	}
}