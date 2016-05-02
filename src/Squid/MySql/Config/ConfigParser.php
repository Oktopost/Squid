<?php
namespace Squid\MySql\Config;


class ConfigParser 
{
	use \Objection\TStaticClass;
	
	
	private static $MAP = 
	[
		'db'	=> ['db', 'database', 'dbname'],
		'host'	=> ['host'],
		'pass'	=> ['pass', 'password', 'pwd'],
		'user'	=> ['user', 'username'],
		'port'	=> ['port']
	];
	
	
	/**
	 * @param string $default
	 * @param string $sectionName
	 * @param array $config
	 * @return string
	 */
	private static function getValue($default, $sectionName, array $config)
	{
		foreach (self::$MAP[$sectionName] as $options)
		{
			if (isset($config[$options]) && is_string($config[$options])) 
				return $config[$options];
		}
		
		return $default;
	}
	
	
	/**
	 * @param array $config
	 * @return MySqlConnectionConfig
	 */
	public static function parse(array $config)
	{
		$config = array_change_key_case($config, CASE_LOWER);
		$object = new MySqlConnectionConfig();
		
		$object->DB		= self::getValue('', 'db', $config);
		$object->Host	= self::getValue('', 'host', $config);
		$object->Port	= (int)self::getValue(3306, 'port', $config);
		$object->Pass	= self::getValue('', 'pass', $config);
		$object->User	= self::getValue('', 'user', $config);
		
		return $object;
	}
}