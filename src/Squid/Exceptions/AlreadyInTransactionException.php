<?php
namespace Squid\Exceptions;


class AlreadyInTransactionException extends SquidException 
{
	public function __construct()
	{
		parent::__construct('A transaction was already started!');
	}
}