<?php
namespace Squid\MySql\Impl\Connectors\Objects\Join\OneToOne;


use Squid\Exceptions\SquidException;

use Squid\MySql\Connectors\Objects\Join\OneToOne\IOneToOneIdConnector;
use Squid\MySql\Connectors\Objects\Generic\IGenericIdConnector;


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
}