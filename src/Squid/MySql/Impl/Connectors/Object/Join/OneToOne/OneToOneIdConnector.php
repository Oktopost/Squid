<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneIdConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;


class OneToOneIdConnector extends AbstractOneToOneIdConnector implements IOneToOneIdConnector
{
	/** @var IGenericIdConnector|string */
	private $connector;
	
	
	protected function getPrimaryIdConnector(): IGenericIdConnector
	{
		if (!$this->connector)
			throw new SquidException('setPrimaryConnector must be called before using OneToOneConnector');
		
		if (is_string($this->connector))
		{
			$this->connector = \Squid::skeleton($this->connector);
		}
		
		return $this->connector;
	}
	
	
	/**
	 * @param IGenericIdConnector|string $connector
	 * @return static
	 */
	public function setPrimaryConnector($connector)
	{
		$this->connector = $connector;
		return $this;
	}
	
	
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
		return $this->populate($object);
	}
}