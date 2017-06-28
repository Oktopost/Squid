<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use Squid\MySql\Connectors\Object\IIdConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;
use Squid\MySql\Impl\Connectors\Object\IdConnector;


/**
 * @mixin AbstractORMConnector
 * @mixin IIdConnector
 */
trait TIdDecorator
{
	use TIdKeyConsumer;
	
	
	/** @var IIdConnector */
	private $_idConnector;
	
	
	private function _getIdConnector(): IIdConnector
	{
		if (!$this->_idConnector)
		{
			$this->_idConnector = new IdConnector($this);
			$this->_idConnector->setIdKey($this->getIdKey());
		}
		
		return $this->_idConnector;
	}
	
	
	public function deleteById($id)
	{
		// TODO: Implement deleteById() method.
	}

	public function loadById($id)
	{
		// TODO: Implement loadById() method.
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