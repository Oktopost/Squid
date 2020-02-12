<?php
namespace Squid\MySql\Impl\Connectors\Utils\Objects;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Impl\Connectors\Internal\Map\MapFactory;


trait TObjectMapConnector
{
	/** @var IRowMap */
	private $_map;
	
	
	private function getMap(): IRowMap
	{
		if (!$this->_map)
			throw new SquidException('setObjectMap must be called before using current connector!');
		
		return $this->_map;
	}
	
	
	/**
	 * @param mixed $mapper
	 * @param array|null $excludeFields Used if $parent is a LiteObject class name
	 * @return static
	 */
	public function setObjectMap($mapper, ?array $excludeFields = null)
	{
		$this->_map = MapFactory::create($mapper, $excludeFields);
		return $this;
	}
}