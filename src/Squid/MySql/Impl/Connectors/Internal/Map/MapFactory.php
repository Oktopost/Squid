<?php
namespace Squid\MySql\Impl\Connectors\Internal\Map;


use Squid\MySql\Impl\Connectors\Internal\Map\Maps\ArrayMapper;
use Squid\MySql\Impl\Connectors\Internal\Map\Maps\DummyMapper;
use Squid\MySql\Impl\Connectors\Internal\Map\Maps\LiteObjectMapper;
use Squid\MySql\Impl\Connectors\Internal\Map\Maps\LiteObjectSimpleMapper;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\Exceptions\SquidException;

use Objection\Mapper;


class MapFactory
{
	/**
	 * @param mixed $data
	 * @param array|null $excludeFields Used if $parent is a LiteObject class name
	 * @return IRowMap
	 */
	public static function create($data = null, ?array $excludeFields = null): IRowMap
	{
		if (is_null($data))
		{
			return new DummyMapper();
		}
		else if ($data instanceof IRowMap)
		{
			return $data;
		}
		else if (is_string($data))
		{
			new LiteObjectSimpleMapper($data, $excludeFields ?: []);
		}
		else if (is_array($data))
		{
			return new ArrayMapper($data);
		}
		else if ($data instanceof Mapper)
		{
			return new LiteObjectMapper($data);
		}
		
		throw new SquidException('Can not convert target object into a mapper');
	}
}