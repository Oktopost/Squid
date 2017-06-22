<?php
namespace Squid\MySql\Impl\Connectors\Internal\Map;


use Squid\MySql\Impl\Connectors\Internal\Map\Maps\ArrayMapper;
use Squid\MySql\Impl\Connectors\Internal\Map\Maps\DummyMapper;
use Squid\MySql\Impl\Connectors\Internal\Map\Maps\LiteObjectMapper;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\Exceptions\SquidException;

use Objection\Mapper;
use Objection\Mappers;


class MapFactory
{
	public static function create($data = null): IRowMap
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
			return new LiteObjectMapper(Mappers::simple()->setDefaultClassName($data));
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