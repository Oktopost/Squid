<?php
namespace Squid\MySql\Impl\Connectors\Internal\Object\CRUD\IdentityInsert;


use Squid\Exceptions\SquidException;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


class AutoincrementInsert extends AbstractIdentityInsert
{
	private $id;
	
	
	public function __construct(AbstractORMConnector $connector, string $idProperty)
	{
		parent::__construct($connector);
		$this->id = $idProperty;
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
			$item->{$this->id} = null;
		}
		
		$res = $this->doInsert($object, false);
		
		if ($res)
		{
			$id = $this->getConnector()->controller()->lastId();
			
			foreach ($object as $item)
			{
				$item->{$this->id} = $id++;
			}
		}
		
		return $res;
	}
}