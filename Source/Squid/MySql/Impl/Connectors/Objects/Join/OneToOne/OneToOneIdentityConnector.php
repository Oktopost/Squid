<?php
namespace Squid\MySql\Impl\Connectors\Objects\Join\OneToOne;


use Squid\MySql\Connectors\Objects\Generic\IGenericIdentityConnector;
use Squid\MySql\Connectors\Objects\Join\OneToOne\IOneToOneIdentityConnector;

use Squid\Exceptions\SquidException;


class OneToOneIdentityConnector extends AbstractOneToOneIdentityConnector implements IOneToOneIdentityConnector
{
	/** @var IGenericIdentityConnector|string */
	private $primaryConnector;
	
	
	protected function getPrimaryIdentityConnector(): IGenericIdentityConnector
	{
		if (!$this->primaryConnector)
			throw new SquidException('setPrimaryConnector must be called before using OneToOneConnector');
		
		if (is_string($this->primaryConnector))
		{
			$this->primaryConnector = \Squid::skeleton($this->primaryConnector);
		}
		
		return $this->primaryConnector;
	}
	
	
	/**
	 * @param IGenericIdentityConnector|string $connector
	 * @return static
	 */
	public function setPrimaryConnector($connector)
	{
		$this->primaryConnector = $connector;
		return $this;
	}
}