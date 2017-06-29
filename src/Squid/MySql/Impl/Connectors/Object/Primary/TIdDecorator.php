<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use Squid\MySql\Connectors\Object\IIdConnector;
use Squid\MySql\Connectors\Object\IIdentityConnector;

use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityDecorator;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


/**
 * @mixin AbstractORMConnector
 * @mixin IIdConnector
 */
trait TIdDecorator
{
	use TIdKeyConsumer;
	use TIdentityDecorator;
	
	
	/** @var IIdConnector */
	private $_idConnector;
	
	
	protected function getIdConnector(): IIdConnector
	{
		if (!$this->_idConnector)
		{
			$this->_idConnector = new DecoratedIdConnector($this);
			$this->_idConnector
				->setIdKey($this->getIdKey())
				->setGenericObjectConnector($this->getGenericObjectConnector());
		}
		
		return $this->_idConnector;
	}
	
	protected function getIdentityConnector(): IIdentityConnector
	{
		return $this->getIdConnector();
	}
	

	public function deleteById($id)
	{
		return $this->getIdConnector()->deleteById($id);
	}

	public function loadById($id)
	{
		return $this->getIdConnector()->loadById($id);
	}
}