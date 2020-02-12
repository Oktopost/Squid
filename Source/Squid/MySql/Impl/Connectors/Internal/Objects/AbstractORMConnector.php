<?php
namespace Squid\MySql\Impl\Connectors\Internal\Objects;


use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Objects\IORMConnector;
use Squid\MySql\Impl\Connectors\Internal\Map\MapFactory;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


abstract class AbstractORMConnector extends AbstractSingleTableConnector implements IORMConnector
{
	/** @var IRowMap */
	private $map;


	/**
	 * Create new ORM Connector. Parameter can be another ORM connector with same object/table config,
	 * or any other setting that can be parsed into IRawMap (class name, Mapper or another IRawMap).
	 * @param IORMConnector|IRowMap|string $parent
	 * @param array|null $excludeFields Used if $parent is a LiteObject class name
	 */
	public function __construct($parent = null, ?array $excludeFields = null)
	{
		if ($parent instanceof AbstractORMConnector)
		{
			parent::__construct($parent);
			$this->map = $parent->getObjectMap();
		}
		else 
		{
			parent::__construct();
			
			if (!is_null($parent))
			{
				$this->map = MapFactory::create($parent, $excludeFields);
			}
		}
	}


	/**
	 * @param mixed $mapper
	 * @param array|null $excludeFields Used if $parent is a LiteObject class name
	 * @return static
	 */
	public function setObjectMap($mapper, ?array $excludeFields = null)
	{
		$this->map = MapFactory::create($mapper, $excludeFields);
		return $this;
	}

	/**
	 * @return IRowMap
	 */
	public function getObjectMap(): IRowMap
	{
		return $this->map;
	}
}