<?php
namespace Squid\MySql\Config;


use PHPUnit\Framework\TestCase;
use Squid\MySql;


class ConfigParserTest extends TestCase
{
	public function test_PassEmptyArray_EmptyConfigSet()
	{
		$result = ConfigParser::parse([]);
		
		$data = array_filter($result->toArray());
		
		unset($data['Version']);
		unset($data['Port']);
		unset($data['Properties']);
		
		self::assertEmpty($data);
	}
	
	
	public function test_FieldCaseIsIgnored()
	{
		$result = ConfigParser::parse(['HoSt' => 'abc']);
		self::assertEquals('abc', $result->Host);
	}
	
	
	
	public function test_PortSet()
	{
		$result = ConfigParser::parse(['port' => 123]);
		self::assertEquals(123, $result->Port);
	}
	
	public function test_VersionSet()
	{
		$result = ConfigParser::parse(['version' => '7.0']);
		self::assertEquals('7.0', $result->Version);
	}
	
	public function test_HostSet()
	{
		$result = ConfigParser::parse(['host' => 'abc']);
		self::assertEquals('abc', $result->Host);
	}
	
	public function test_PassSet()
	{
		$result = ConfigParser::parse(['pass' => 'abc']);
		self::assertEquals('abc', $result->Pass);
		
		$result = ConfigParser::parse(['password' => 'abc']);
		self::assertEquals('abc', $result->Pass);
		
		$result = ConfigParser::parse(['pwd' => 'abc']);
		self::assertEquals('abc', $result->Pass);
	}
	
	public function test_DBSet()
	{
		$result = ConfigParser::parse(['db' => 'abc']);
		self::assertEquals('abc', $result->DB);
		
		$result = ConfigParser::parse(['database' => 'abc']);
		self::assertEquals('abc', $result->DB);
		
		$result = ConfigParser::parse(['dbname' => 'abc']);
		self::assertEquals('abc', $result->DB);
	}
	
	public function test_FlagsSet()
	{
		$result = ConfigParser::parse(['flags' => [1, 2]]);
		self::assertEquals([1, 2], $result->PDOFlags);
		
		$result = ConfigParser::parse(['attribute' => [1, 2]]);
		self::assertEquals([1, 2], $result->PDOFlags);
	}
	
	public function test_UserSet()
	{
		$result = ConfigParser::parse(['user' => 'abc']);
		self::assertEquals('abc', $result->User);
		
		$result = ConfigParser::parse(['username' => 'abc']);
		self::assertEquals('abc', $result->User);
	}
	
	public function test_InvalidPropertyPassed_ExceptionThrown()
	{
		$this->expectException(\Squid\Exceptions\InvalidConfigPropertyException::class);
		
		ConfigParser::parse(['properties' => ['a' => true]]);
	}
	
	public function test_InvalidPropertyValuePassed_ExceptionThrown()
	{
		$this->expectException(\Squid\Exceptions\InvalidConfigPropertyValueException::class);
		
		ConfigParser::parse(['properties' => [MySql::PROP_ID_FIELD => '']]);
	}
	
	public function test_ValidPropertyPassed_PropertySet()
	{
		$config = ConfigParser::parse(['properties' => [MySql::PROP_ID_FIELD => 'ABC']]);
		
		self::assertEquals('ABC', $config->Properties[MySql::PROP_ID_FIELD]); 
	}
	
	public function test_DefaultPropertyValuesSet()
	{
		$config = ConfigParser::parse(['properties' => []]);
		
		self::assertEquals(
			MySqlConnectionConfig::DEFAULT_PROPERTIES,
			$config->Properties);
	}
}