<?php
namespace Squid\Utils;


use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class Logging
{
	private static $logger;
	
	
	public static function setLogger(LoggerInterface $logger): void
	{
		self::$logger = $logger;
	}
	
	public static function getLogger(): LoggerInterface
	{
		return self::$logger ?? new NullLogger();
	}
}