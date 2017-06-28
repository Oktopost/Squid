<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


use Squid\MySql\Connectors\Object\IIdentityConnector;

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
			$this->_identityConnector = new IdentityConnector($this);
			$this->_identityConnector->setPrimaryKeys($this->getPrimaryKeys());
		}
		
		return $this->_identityConnector;
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