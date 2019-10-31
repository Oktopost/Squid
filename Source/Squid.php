<?php
use Squid\MySql;
use Squid\SkeletonInit;

use Skeleton\Skeleton;


class Squid
{
	/**
	 * @return MySql
	 */
	public static function MySql()
	{
		return SkeletonInit::skeleton(MySql::class);
	}
	
	/**
	 * @param string $item
	 * @return Skeleton|mixed
	 */
	public static function skeleton($item = null)
	{
		return SkeletonInit::skeleton($item);
	}
}