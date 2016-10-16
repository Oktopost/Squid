<?php
use Squid\MySql;

use Skeleton\Type;
use Skeleton\Skeleton;
use Skeleton\ConfigLoader\PrefixDirectoryConfigLoader;


/**
 * @method static MySql MySql()
 */
class Squid
{
	use \Skeleton\TStaticModule;
	
	
	/** @var Skeleton */
	private static $skeleton = null;
	
	
	private static function defaultSetup()
	{
		self::$skeleton->set(MySql::class, MySql::class, Type::Singleton);
	}
	
	private static function setUp()
	{
		self::$skeleton = new Skeleton();
		
		self::$skeleton
			->enableKnot()
			->registerGlobalFor('Squid')
			->setConfigLoader(new PrefixDirectoryConfigLoader('Squid', __DIR__ . '/../skeleton'));
		
		self::defaultSetup();
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