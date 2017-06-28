<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\IIdentityConnector;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\IdentityConnector;
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
	
	
	private function _getIdentityConnector(): IIdentityConnector
	{
		if (!$this->_identityConnector)
		{
			$this->_identityConnector = new DecoratedIdentityConnector($this);
			$this->_identityConnector
				->setPrimaryKeys($this->getPrimaryKeys())
				->setGenericObjectConnector($this->getGenericObjectConnector());
		}
		
		return $this->_identityConnector;
	}
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector 
	{
		return new GenericObjectConnector($this);
	}
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		// TODO: Implement delete() method.
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