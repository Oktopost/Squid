<?php
namespace Squid;


use Squid\MySql\ConfigFacade;


class MySql
{
	use \Objection\TStaticClass;
	
	
	/** @var ConfigFacade */
	private $configFacade;
	
	
	/**
	 * @return ConfigFacade
	 */
	public function config() 
	{
		return $this->configFacade;
	}
	
	/** 
	 * @return 
	 */
	public function connector($name) 
	{
		
	}
}