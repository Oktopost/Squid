<?php
namespace Squid\MySql\Config;


use Squid\Exceptions\InvalidConfigPropertyException;
use Squid\Exceptions\InvalidConfigPropertyValueException;

use Structura\Arrays;


class ConfigParser 
{
	use \Traitor\TStaticClass;
	
	
	private static $TRUE_VALUES = ['1', 'on', 'true'];
	private static $MAP = 
	[
		'db'		=> ['db', 'database', 'dbname'],
		'host'		=> ['host'],
		'pass'		=> ['pass', 'password', 'pwd'],
		'user'		=> ['user', 'username'],
		'port'		=> ['port'],
		'flags'		=> ['flags', 'attribute'],
		'version'	=> ['version'],
		'charset'	=> ['charset'],
		'reuse'		=> ['reuse', 'reuse-connection']
	];
	
	
	/**
	 * @param string|array $default
	 * @param string $sectionName
	 * @param array $config
	 * @return string
	 */
	private static function getValue($default, $sectionName, array $config)
	{
		foreach (self::$MAP[$sectionName] as $options)
		{
			if (isset($config[$options])) 
				return $config[$options];
		}
		
		return $default;
	}
	
	private static function getBoolean(bool $default, string $sectionName, array $config): bool
	{
		$value = self::getValue($default ? '1' : '0', $sectionName, $config);
		$value = strtolower(trim($value));
		
		return in_array($value, self::$TRUE_VALUES);
	}
	
	private static function getProperties(array $config): array
	{
		$result = MySqlConnectionConfig::DEFAULT_PROPERTIES;
		$properties = $config['properties'] ?? [];
		
		$invalid = array_diff(array_keys($properties), array_keys($result));
		
		if ($invalid)
		{
			throw new InvalidConfigPropertyException(Arrays::first($invalid));
		}
		
		
		// Validate default ID field
		if (array_key_exists(Property::PROP_ID_FIELD, $properties))
		{
			$value = (string)$properties[Property::PROP_ID_FIELD];
			
			if (!$value)
			{
				throw new InvalidConfigPropertyValueException(
					Property::PROP_ID_FIELD, $value, 'Default ID can not be empty');
			}
			
			$result[Property::PROP_ID_FIELD] = $value;
		}
		
		// Validate LIKE escape character 
		if (array_key_exists(Property::PROP_LIKE_ESCAPE_CHAR, $properties))
		{
			$value = (string)$properties[Property::PROP_LIKE_ESCAPE_CHAR];
			
			if (strlen($value) != 1)
			{
				throw new InvalidConfigPropertyValueException(
					Property::PROP_LIKE_ESCAPE_CHAR, $value, 'LIKE escape character must be a string of size 1');
			}
			
			$result[Property::PROP_LIKE_ESCAPE_CHAR] = $value;
		}
		
		
		return $result;
	}
	
	
	/**
	 * @param array $config
	 * @return MySqlConnectionConfig
	 */
	public static function parse(array $config)
	{
		$config = array_change_key_case($config, CASE_LOWER);
		$object = new MySqlConnectionConfig();
		
		$object->DB			= self::getValue('', 'db', $config);
		$object->Host		= self::getValue('', 'host', $config);
		$object->Port		= (int)self::getValue(3306, 'port', $config);
		$object->Pass		= self::getValue('', 'pass', $config);
		$object->User		= self::getValue('', 'user', $config);
		$object->PDOFlags	= self::getValue([], 'flags', $config);
		$object->Version	= self::getValue($object->Version, 'version', $config);
		
		$object->CharSet	= self::getValue(null, 'charset', $config);
		
		$object->Properties			= self::getProperties($config);
		$object->ReuseConnection	= self::getBoolean(false, 'reuse', $config);
		
		return $object;
	}
}