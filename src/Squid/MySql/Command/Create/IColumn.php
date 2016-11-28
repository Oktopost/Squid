<?php
namespace Squid\MySql\Command\Create;


interface IColumn
{
	/**
	 * @return string
	 */
	public function generate();
}