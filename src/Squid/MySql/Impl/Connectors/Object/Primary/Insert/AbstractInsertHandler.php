<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary\Insert;


use Squid\Exceptions\SquidUsageException;

use Squid\MySql\Connectors\Object\CRUD\Identity\IIdentityInsert;
use Squid\MySql\Connectors\Object\Primary\IInsertHandler;


abstract class AbstractInsertHandler implements IInsertHandler
{
	private $field;
	
	/** @var IIdentityInsert|callable */
	private $handler;
	
	
	protected function idField(): string
	{
		return $this->field;
	}
	
	protected function doInsert(array $objects)
	{
		$handler = $this->handler;
		
		if (is_callable($handler))
		{
			return $handler($objects);
		}
		else if ($handler instanceof IIdentityInsert)
		{
			return $handler->insert($objects);
		}
		else
		{
			throw new SquidUsageException('Insert handler must be a callback or IIdentityInsert instance');
		}
	}
	
	
	/**
	 * @param callable|IIdentityInsert $insert
	 * @return static|IInsertHandler
	 */
	public function setInsertProvider($insert): IInsertHandler
	{
		$this->handler = $insert;
		return $this;
	}

	/**
	 * @param string $field
	 * @return static|IInsertHandler
	 */
	public function setIdField(string $field): IInsertHandler
	{
		$this->field = $field;
		return $this;
	}
}