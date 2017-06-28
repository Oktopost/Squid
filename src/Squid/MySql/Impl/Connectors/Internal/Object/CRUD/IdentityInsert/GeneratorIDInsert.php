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
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($object, bool $ignore = false)
	{
		if (!is_array($object))
			$object = [$object];
		
		$ids = $this->generator->generate($this->getORMConnector()->getTableName(), $object);
		
		try
		{
			return $this->doInsert($object);
		}
		finally
		{
			$this->generator->release($ids);
		}
	}
}