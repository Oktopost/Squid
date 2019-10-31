<?php
namespace Squid\Exceptions;


class NotInTransactionException extends SquidException 
{
	public function __construct()
	{
		parent::__construct('Can not execute commit/rollback when not in transaction!');
	}
}