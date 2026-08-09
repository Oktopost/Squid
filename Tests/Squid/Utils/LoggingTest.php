<?php
namespace Squid\Utils;


use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class LoggingTest extends TestCase
{
	protected function tearDown(): void
	{
		Logging::setLogger(new NullLogger());
	}
	
	private function resetLogger(): void
	{
		$reflection = new \ReflectionClass(Logging::class);
		$property = $reflection->getProperty('logger');
		$property->setValue(null, null);
	}
	
	public function test_getLogger_LoggerNotSet_ReturnNullLogger(): void
	{
		$this->resetLogger();
		
		self::assertInstanceOf(NullLogger::class, Logging::getLogger());
	}
	
	public function test_getLogger_LoggerSet_ReturnLogger(): void
	{
		$logger = $this->createMock(LoggerInterface::class);
		
		Logging::setLogger($logger);
		
		self::assertSame($logger, Logging::getLogger());
	}
}
