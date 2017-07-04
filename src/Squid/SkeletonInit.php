<?php
namespace Squid;


use Skeleton\Skeleton;
use Skeleton\Base\ISkeletonInit;
use Skeleton\ConfigLoader\PrefixDirectoryConfigLoader;


class SkeletonInit implements ISkeletonInit
{
	/** @var Skeleton */
	private static $skeleton = null;
	
	
	private static function configureSkeleton(): void
	{
		if (self::$skeleton)
		{
			return;
		}
		
		self::$skeleton = new Skeleton();
		self::$skeleton
			->enableKnot()
			->registerGlobalFor('Squid')
			->useGlobal()
			->setConfigLoader(new PrefixDirectoryConfigLoader('Squid', __DIR__ . '/../../skeleton'));
		
		self::$skeleton->set(MySql::class, MySql::class);
	}
	
	
	/**
	 * @param string|null $interface
	 * @return mixed|Skeleton
	 */
	public static function skeleton(?string $interface = null)
	{
		if (!self::$skeleton)
		{
			self::configureSkeleton();
		}
		
		if ($interface)
		{
			return self::$skeleton->get($interface);
		}
		
		return self::$skeleton;
	}
}