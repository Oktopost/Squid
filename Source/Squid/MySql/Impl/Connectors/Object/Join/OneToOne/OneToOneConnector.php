<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;


class OneToOneConnector extends AbstractOneToOneConnector
{
	/** @var IGenericObjectConnector|string */
	private $primaryConnector;
	
	
	protected function getPrimary(): IGenericObjectConnector
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
	 * @param IGenericObjectConnector|string $connector
	 * @return static
	 */
	public function setPrimaryConnector($connector)
	{
		$this->primaryConnector = $connector;
		return $this;
	}
}