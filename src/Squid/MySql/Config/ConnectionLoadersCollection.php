<?php
namespace Squid\MySql\Config;


use Squid\Exceptions\SquidException;


class ConfigLoadersCollection implements IConfigLoader 
{
	/** @var IConfigLoader[] */
	private $collections;
	
	
	/**
	 * @param IConfigLoader $loader
	 */
	public function add(IConfigLoader $loader) 
	{
		$this->collections[] = $loader;
	}


	/**
	 * @param string $connName
	 * @return array
	 * @throws SquidException
	 */
	public function getConfig($connName) 
	{
		foreach ($this->collections as $loader) 
		{
			if ($loader->hasConfig($connName)) 
			{
				return $loader->getConfig($connName);
			}
		}
		
		throw new SquidException("Connection config '$connName' does not exist");
	}
	
	/**
	 * @param string $connName
	 * @return bool
	 */
	public function hasConfig($connName) 
	{
		foreach ($this->collections as $loader) 
		{
			if ($loader->hasConfig($connName)) 
			{
				return true;
			}
		}
		
		return false;
	}
}