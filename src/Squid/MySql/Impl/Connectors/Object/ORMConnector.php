<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Impl\Connectors\SingleTableConnector;
use Squid\MySql\Impl\Connectors\Map\MapFactory;


class ORMConnector extends SingleTableConnector implements IORMConnector
{
	/** @var IRowMap */
	private $mapper;
	
	
	public function __construct(IORMConnector $parent = null)
	{
		parent::__construct($parent);
		
		if ($parent)
		{
			$this->mapper = $parent->getMapper();
		}
	}


	/**
	 * @param mixed $mapper
	 */
	public function setMapper($mapper)
	{
		$this->mapper = MapFactory::create($mapper);
	}

	/**
	 * @return IRowMap
	 */
	public function getMapper(): IRowMap
	{
		return $this->mapper;
	}
}