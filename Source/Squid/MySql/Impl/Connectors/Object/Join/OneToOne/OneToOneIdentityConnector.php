<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;
use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneIdentityConnector;

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