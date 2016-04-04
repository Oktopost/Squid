<?php
namespace Squid\MySql\Connection;


use Objection\LiteSetup;
use Objection\LiteObject;


/** 
 * @property string	$DB
 * @property string	$Host
 * @property int	$Port
 * @property string	$User
 * @property string	$Pass
 */
class MySqlConnectionConfig extends LiteObject 
{
	/**
	 * @return array
	 */
	protected function _setup() 
	{
		return [
			'DB'	=> LiteSetup::createString(''),
			'Host'	=> LiteSetup::createString('localhost'),
			'Port'	=> LiteSetup::createInt(3306),
			'User'	=> LiteSetup::createString(''),
			'Pass'	=> LiteSetup::createString('')
		];
	}
	
	
	/**
	 * @return string
	 */
	public function getPDOConnectionString() {
		$connString = "mysql:host={$this->Host};port={$this->Port}";
		
		if ($this->DB) {
			$connString .= ";dbname={$this->DB}";
		}
		
		return $connString;
	}
}