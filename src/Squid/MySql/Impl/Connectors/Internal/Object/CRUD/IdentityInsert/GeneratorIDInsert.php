<?php
namespace Squid\MySql\Impl\Connectors\Internal\Object\CRUD\IdentityInsert;


use Squid\MySql\Connectors\Object\ID\IIDGenerator;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


class GeneratorIDInsert extends AbstractIdentityInsert
{
	private $name;
	
	/** @var IIDGenerator */
	private $generator;

	
	public function __construct(AbstractORMConnector $connector, IIDGenerator $generator)
	{
		parent::__construct($connector);
		$this->generator = $generator;
	}
	

	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		if (!is_array($objects))
			$objects = [$objects];
		
		$ids = $this->generator->generate($this->getORMConnector()->getTableName(), $objects);
		
		try
		{
			return $this->doInsert($objects);
		}
		finally
		{
			$this->generator->release($ids);
		}
	}
}