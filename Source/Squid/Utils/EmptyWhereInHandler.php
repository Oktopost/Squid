<?php
namespace Squid\Utils;


use Squid\MySql\Command\IWithWhere;
use Squid\Exceptions\SquidException;
use Traitor\TStaticClass;


class EmptyWhereInHandler
{
	use TStaticClass;
	
	
	/** @var callable|null */
	private static $handler = null; 
	
	
	private static function defaultHandler(string $field): void
	{
		throw new SquidException("Empty values set passed to whereIn for field $field");
	}
	
	
	public static function handle(string|array $field, IWithWhere $where): void
	{
		if (is_array($field))
		{
			$field = implode(',', $field);
		}
		
		if (self::$handler)
		{
			$callback = self::$handler;
			$callback($field, $where);
		}
		else
		{
			self::defaultHandler($field);
		}
	}
	
	public static function set(callable $c): void
	{
		self::$handler = $c;
	}
}