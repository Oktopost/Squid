<?php
namespace lib;


use Traitor\TStaticClass;
use Skeleton\UnitTestSkeleton;


class SkeletonOverride
{
	use TStaticClass;
	
	
	private static $override;
	
	
	public static function get(): UnitTestSkeleton
	{
		if (!self::$override)
			self::$override = new UnitTestSkeleton(\Squid::skeleton());
		
		return self::$override;
	}
	
	public static function clear()
	{
		self::get()->clear();
	}
	
	public static function set($key, $val)
	{
		self::get()->override($key, $val);
	}
}