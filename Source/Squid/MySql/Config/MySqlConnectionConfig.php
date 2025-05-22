<?php
namespace Squid\MySql\Config;


use Objection\LiteSetup;
use Objection\LiteObject;


/** 
 * @property string	$DB
 * @property string	$Host
 * @property int	$Port
 * @property string	$User
 * @property string	$Pass
 * @property bool	$ReuseConnection
 * @property array	$PDOFlags
 * @property string	$Version
 * @property array	$Properties
 * @property string $CharSet
 */
class MySqlConnectionConfig extends LiteObject 
{
	public const DEFAULT_PROPERTIES = [
		Property::PROP_ID_FIELD			=> 'Id',
		Property::PROP_LIKE_ESCAPE_CHAR	=> '\\',
	];
	
	
	public function __debugInfo()
	{
		$info = parent::__debugInfo();
		
		if ($info['Pass'])
			$info['Pass'] = '***';
		
		return $info;
	}
	
	
	/**
	 * @return array
	 */
	protected function _setup() 
	{
		return [
			'DB'				=> LiteSetup::createString(),
			'Host'				=> LiteSetup::createString('localhost'),
			'Port'				=> LiteSetup::createInt(3306),
			'User'				=> LiteSetup::createString(),
			'Pass'				=> LiteSetup::createString(),
			'ReuseConnection'	=> LiteSetup::createBool(false),
			'PDOFlags'			=> LiteSetup::createArray(),
			'Version'			=> LiteSetup::createString('5.6'),
			'Properties'		=> LiteSetup::createArray(self::DEFAULT_PROPERTIES),
			'CharSet'			=> LiteSetup::createString(null)
		];
	}
	
	
	/**
	 * @return string
	 */
	public function getPDOConnectionString()
	{
		$connString = "mysql:host={$this->Host};port={$this->Port}";
		
		if ($this->DB)
		{
			$connString .= ";dbname={$this->DB}";
		}
		
		if ($this->CharSet)
		{
			$connString .= ";charset={$this->CharSet}";
		}
		
		return $connString;
	}
}