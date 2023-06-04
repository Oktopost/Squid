<?php
namespace Squid\MySql\Query;


use Squid\MySql\Command\ICmdSelect;


interface IQuery
{
	public function select(): ICmdSelect;
	
	public function assemble(): string;
	public function bind(): array;
	
	public function query(): array;
	
	/**
	 * @return null|mixed
	 */
	public function queryFirst();
}