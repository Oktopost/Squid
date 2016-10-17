<?php
namespace Squid\MySql\Impl\Connection\Executors\RetryOnError;


use Squid\MySql\Impl\Connection\Executors\RetryOnError\Base\IErrorValidator;


class ValidatorsSet
{
	/** @var IErrorValidator[]|string[] */
	private $validators = [];
	
	
	/**
	 * @param string[]|IErrorValidator[] $validators
	 */
	public function add(...$validators)
	{
		$this->validators = array_merge($this->validators, $validators);
	}


	/**
	 * @param \Exception $e
	 * @return array|false
	 */
	public function getConfigFor(\Exception $e)
	{
		foreach ($this->validators as $validator)
		{
			if (is_string($validator))
			{
				$validator = new $validator;
			}
			
			if ($validator->isHandled($e))
			{
				return $validator->config();
			}
		}
		
		return false;
	}
}