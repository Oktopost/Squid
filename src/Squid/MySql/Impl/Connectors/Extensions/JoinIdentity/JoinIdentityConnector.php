<?php
namespace Squid\MySql\Impl\Connectors\Extensions\JoinIdentity;


use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;
use Squid\MySql\Connectors\Object\ObjectSelect\IQueryConnector;
use Squid\MySql\Connectors\Object\ObjectSelect\ICmdObjectSelect;
use Squid\MySql\Connectors\Extensions\JoinIdentity\IJoinIdentityConfig;

use Squid\MySql\Impl\Connectors\Internal\Connector;
use Squid\MySql\Impl\Connectors\Extensions\JoinIdentity\Utils\JoinQueryDecorator;
use Squid\MySql\Impl\Connectors\Extensions\JoinIdentity\Utils\IJoinedDataLoader;

use Squid\Exceptions\SquidException;


class JoinIdentityConnector extends Connector implements 
	IIdentifiedObjectConnector, 
	IJoinedDataLoader, 
	IQueryConnector
{
	/** @var IIdentifiedObjectConnector */
	private $primaryConnector;
	
	/** @var IIdentifiedObjectConnector */
	private $dataConnector;
	
	/** @var IJoinIdentityConfig */
	private $config;
	
	
	private function invokeSaveMethod($object, callable $method)
	{
		$res = $this->primaryConnector->$method($object);
		
		if ($res !== false)
		{
			$this->config->beforeDataSave($object);
			$data = $this->config->getData($object);
			
			if ($data)
			{
				$res = $this->dataConnector->$method($data);
			}
		}
		
		return $res;
	}
	
	
	public function setObjectConnector(IIdentifiedObjectConnector $connector): JoinIdentityConnector
	{
		$this->primaryConnector = $connector;
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
		$object = $this->primaryConnector->load($id);
		
		if ($object)
		{
			$this->loadData($object);
		}
		
		return $object;
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
		
		return $this->primaryConnector->deleteById($id);
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		$res = $this->primaryConnector->delete($object);
		
		if ($res !== false && $this->config->onDeleteObjectCascadeData())
		{
			$data = $this->config->getData($object);
			
			if ($data)
			{
				$res = $this->dataConnector->delete($data);
			}
		}
		
		return $res;
	}
	
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false)
	{
		$res = $this->primaryConnector->insert($object, $ignore);
		
		if ($res !== false)
		{
			$this->config->beforeDataSave($object);
			$data = $this->config->getData($object);
			
			if ($data)
			{
				$res = $this->dataConnector->insert($data, $ignore);
			}
		}
		
		return $res;
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function save($object)
	{
		return $this->invokeSaveMethod($object, __FUNCTION__);
	}
	
	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		return $this->invokeSaveMethod($object, __FUNCTION__);
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		return $this->invokeSaveMethod($object, __FUNCTION__);
	}
	
	/**
	 * @param $object
	 * @return bool
	 */
	public function loadData($object)
	{
		$identifier = $this->config->getDataIdentifier($object);
		$data = $this->dataConnector->load($identifier);
		
		if ($data !== false)
		{
			if (is_array($object))
			{
				$this->config->combineAll($object, $data);
			}
			else
			{
				$this->config->combine($object, $data);
			}
		}
		
		return (bool)$data;
	}

	public function query(): ICmdObjectSelect
	{
		if (!($this->primaryConnector instanceof IQueryConnector))
		{
			throw new SquidException('query operation is available only if ' . 
				'object connector is also an IQueryConnector instance');
		}
		
		return new JoinQueryDecorator($this->primaryConnector->query(), $this);
	}
}