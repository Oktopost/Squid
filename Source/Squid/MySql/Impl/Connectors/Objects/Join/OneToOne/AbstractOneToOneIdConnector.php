<?php
namespace Squid\MySql\Impl\Connectors\Objects\Join\OneToOne;


use Squid\MySql\Connectors\Objects\Generic\IGenericIdConnector;
use Squid\MySql\Connectors\Objects\Generic\IGenericIdentityConnector;
use Squid\MySql\Connectors\Objects\Join\OneToOne\IOneToOneIdConnector;


abstract class AbstractOneToOneIdConnector extends AbstractOneToOneIdentityConnector implements IOneToOneIdConnector
{
	protected function getPrimaryIdentityConnector(): IGenericIdentityConnector
	{
		return $this->getPrimaryIdConnector();
	}
	
	
	protected abstract function getPrimaryIdConnector(): IGenericIdConnector;
	
	
	/**
	 * @param string|array $id
	 * @return int|false
	 */
	public function deleteById($id)
	{
		return $this->getPrimaryIdConnector()->deleteById($id);
	}

	/**
	 * @param string|array $id
	 * @return mixed|null|false
	 */
	public function loadById($id)
	{
		$object = $this->getPrimaryIdConnector()->loadById($id);
		return ($object ? $this->config()->loaded($object) : $object);
	}
	
	/**
	 * @param mixed|array $objects
	 * @return int|false
	 */
	public function save($objects)
	{
		$count = $this->getPrimaryIdConnector()->save($objects);
		
		if ($count === false)
			return false;
		
		$savedCount = $this->config()->saved($objects);
		
		return ($savedCount === false ? false : $count + $savedCount);
	}
}