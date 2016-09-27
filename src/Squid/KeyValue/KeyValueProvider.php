<?php
namespace Squid\KeyValue;


class KeyValueProvider implements IKeyValueProvider
{
	/** @var IKeyValueConnector */
	private $connector;
	
	/** @var callable */
	private $defaultCallback = null;
	
	
	/**
	 * @param callable $callback
	 * @return callable
	 */
	private function getCallback($callback)
	{
		if ($callback)
		{
			return $callback;
		}
		
		if ($this->defaultCallback)
		{
			return $this->defaultCallback;
		}
		
		throw new \Exception('Callback or default callback should be passed to KeyValueProvider');
	}
	
	
	/**
	 * @param IKeyValueConnector $connector
	 */
	public function __construct(IKeyValueConnector $connector) 
	{
		$this->connector = $connector;
	}
	
	
	/**
	 * @param callable $callback
	 */
	public function setDefaultCallback($callback)
	{
		$this->defaultCallback = $callback;
	}
	
	/**
	 * @param string $key
	 * @param callable|null $callback
	 * @return string
	 */
	public function get($key, $callback = null)
	{
		$value = $this->connector->get($key);
		
		if (is_null($value))
		{
			$callback = $this->getCallback($callback); 
			$value = $callback($key);
			
			if (!is_null($value))
			{
				$this->connector->set($key, $value);
			}
		}
		
		return $value;
	}
}