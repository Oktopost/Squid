<?php
namespace Squid\MySql\Command\Create;


interface IIndexable
{
	/**
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function index($name, ...$columns);
	
	/**
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function unique($name, ...$columns);
}