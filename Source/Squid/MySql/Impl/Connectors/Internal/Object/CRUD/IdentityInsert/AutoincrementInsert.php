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
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		if (!is_array($objects))
		{
			$objects = [$objects];
		}
		else if ($ignore && is_array($objects))
		{
			throw new SquidException('Can not use Autoincrement connector for object with the ignore flag on');
		}
		
		foreach ($objects as $item)
		{
			$item->{$this->id} = null;
		}
		
		$res = $this->doInsert($objects, false);
		
		if ($res)
		{
			$id = $this->getConnector()->controller()->lastId();
			
			foreach ($objects as $item)
			{
				$item->{$this->id} = $id++;
			}
		}
		
		return $res;
	}
}