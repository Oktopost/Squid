<?php
namespace Squid\MySql\Impl\Connection\Executors;


use Squid\MySql\Connection\AbstractMySqlExecuteDecorator;

use Squid\MySql\Impl\Connection\Executors\RetryOnError\Validators;
use Squid\MySql\Impl\Connection\Executors\RetryOnError\ValidatorsSet;
use Squid\MySql\Impl\Connection\Executors\RetryOnError\Base\IErrorValidator;


class RetryOnErrorDecorator extends AbstractMySqlExecuteDecorator
{
	/** @var ValidatorsSet */
	private $validators;


	/**
	 * @param \Exception $e
	 * @return array
	 */
	private function getConfig(\Exception $e)
	{
		$config = $this->validators->getConfigFor($e);
		
		if (!$config)
			throw $e;
		
		return $config;
	}
	
	/**
	 * @param \Exception $e
	 * @param string $cmd
	 * @param array $bind
	 * @return mixed
	 */
	private function retry(\Exception $e, $cmd, array $bind = [])
	{
		$config = $this->getConfig($e);
		
		for ($i = 0; $i < $config['retries']; $i++)
		{
			usleep($config['ms-delay'] * 1000000);
			
			try
			{
				return parent::execute($cmd, $bind);
			}
			catch (\Exception $new)
			{
				if (!$this->isSameError($e, $new))
					throw $new;
			}
		}
		
		throw $e;
	}

	/**
	 * @param \Exception $original
	 * @param \Exception $new
	 * @return bool
	 */
	private function isSameError(\Exception $original, \Exception $new)
	{
		return (
			$original->getCode() == $new->getCode() && 
			get_class($original) == get_class($new));
	}
	

	/**
	 * @param string[]|IErrorValidator[] ...$validators
	 */
	public function __construct(...$validators)
	{
		$this->validators = new ValidatorsSet();
		$this->validators->add(...$validators);
	}


	/**
	 * @param string[]|IErrorValidator[] ...$validators
	 * @return $this
	 */
	public function addValidators(...$validators)
	{
		$this->validators->add($validators);
		return $this;
	}
	
	public function execute(string $cmd, array $bind = [])
	{
		try
		{
			return parent::execute($cmd, $bind);
		}
		catch (\Exception $e)
		{
			return $this->retry($e, $cmd, $bind);
		}
	}
	
	
	public static function createSuggested(): RetryOnErrorDecorator
	{
		return new RetryOnErrorDecorator(
			Validators\ConnectionFailed::class,
			Validators\DeadlockFound::class
		);
	}
}