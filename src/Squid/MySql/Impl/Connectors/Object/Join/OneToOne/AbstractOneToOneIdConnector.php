<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;
use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneIdConnector;


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
		
	}
}