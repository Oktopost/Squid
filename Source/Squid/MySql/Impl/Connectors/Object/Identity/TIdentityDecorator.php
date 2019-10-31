<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


use Squid\MySql\Connectors\Object\IIdentityConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


/**
 * @mixin AbstractORMConnector
 * @mixin IIdentityConnector
 */
trait TIdentityDecorator
{
	use TPrimaryKeysConsumer;
	
	
	/** @var IIdentityConnector */
	private $_identityConnector;
	
	/** @var IGenericObjectConnector */
	private $_genericObjectConnector;
	
	
	protected function getIdentityConnector(): IIdentityConnector
	{
		if (!$this->_identityConnector)
		{
			$this->_identityConnector = new DecoratedIdentityConnector();
			$this->_identityConnector
				->setPrimaryKeys($this->getPrimaryKeys())
				->setGenericObjectConnector($this->getGenericObjectConnector());
		}
		
		return $this->_identityConnector;
	}
	
	protected function getGenericObjectConnector(): IGenericObjectConnector 
	{
		if (!$this->_genericObjectConnector)
			$this->_genericObjectConnector = new GenericObjectConnector($this);
		
		return $this->_genericObjectConnector;
	}
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		return $this->getIdentityConnector()->delete($object);
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		return $this->getIdentityConnector()->update($object);
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		return $this->getIdentityConnector()->upsert($object);
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function insert($object)
	{
		return $this->getIdentityConnector()->insert($object);
	}
}