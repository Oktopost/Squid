<?php
namespace Squid\MySql\Impl\Connectors\Object\CRUD\Insert;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Object\CRUD\IObjectInsert;
use Squid\MySql\Impl\Connectors\Object\ORMConnector;
use Squid\MySql\Impl\Connectors\Object\GenericObjectConnector;


class AIObjectInsert extends ORMConnector implements IObjectInsert
{
	private $aiField;


	/**
	 * @param string $field
	 * @return AIObjectInsert|static
	 */
	public function setAIProperty(string $field): AIObjectInsert
	{
		$this->aiField = $field;
		return $this;
	}
	

	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false)
	{
		if (!is_array($object))
		{
			$object = [$object];
		}
		else if ($ignore && is_array($object))
		{
			throw new SquidException('Can not use Autoincrement connector for object with the ignore flag on');
		}
		
		foreach ($object as $item)
		{
			$item->{$this->aiField} = null;
		}
		
		$crud = new GenericObjectConnector($this);
		$res = $crud->insert($object, false);
		
		if ($res)
		{
			$id = $this->getConnector()->controller()->lastId();
			
			foreach ($object as $item)
			{
				$item->{$this->aiField} = $id++;
			}
		}
		
		return $res;
	}
}