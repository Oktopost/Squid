<?php
namespace Squid\Exceptions;


class InvalidConfigPropertyException extends FatalSquidException
{
	public function __construct(string $propertyName)
	{
		parent::__construct("Property $propertyName does not exist");
	}
}