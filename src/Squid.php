<?php
use Squid\MySql;

use Skeleton\Type;
use Skeleton\Skeleton;
use Skeleton\ConfigLoader\PrefixDirectoryConfigLoader;


class Squid
{
	/** @var Skeleton */
	private static $skeleton = null;
	
	
	private static function defaultSetup()
	{
		
	}
	
	private static function setUp()
	{
		self::$skeleton = new Skeleton();
		
		self::$skeleton
			->enableKnot()
			->registerGlobalFor('Squid')
			->setConfigLoader(new PrefixDirectoryConfigLoader('Squid', __DIR__ . '/../skeleton'));
		
		self::$skeleton->set(MySql::class, MySql::class);
	}


	/**
	 * @return MySql
	 */
	public static function MySql()
	{
		return self::skeleton(MySql::class);
	}
	
	/**
	 * @param string $item
	 * @return Skeleton|mixed
	 */
	public static function skeleton($item = null)
	{
		if (!self::$skeleton) 
			self::setUp();
		
		if ($item)
			return self::$skeleton->get($item);
		
		return self::$skeleton;
	}
}