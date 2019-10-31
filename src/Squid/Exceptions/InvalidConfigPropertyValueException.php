<?php
namespace Squid\Exceptions;


class InvalidConfigPropertyValueException extends FatalSquidException
{
	public function __construct(string $name, string $value, ?string $message)
	{
		$errorMessage = "Invalid value '$value'' set for property '$name'.";
		
		if ($message)
		{
			$errorMessage .= " $message";
		}
		
		parent::__construct($errorMessage);
	}
}