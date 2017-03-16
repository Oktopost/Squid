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
 * @property array	$PDOFlags
 */
class MySqlConnectionConfig extends LiteObject 
{
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
			'DB'		=> LiteSetup::createString(),
			'Host'		=> LiteSetup::createString('localhost'),
			'Port'		=> LiteSetup::createInt(3306),
			'User'		=> LiteSetup::createString(),
			'Pass'		=> LiteSetup::createString(),
			'PDOFlags'	=> LiteSetup::createArray()
		];
	}
	
	
	/**
	 * @return string
	 */
	public function getPDOConnectionString()
	{
		$connString = "mysql:host={$this->Host};port={$this->Port}";
		
		if ($this->DB) {
			$connString .= ";dbname={$this->DB}";
		}
		
		return $connString;
	}
}