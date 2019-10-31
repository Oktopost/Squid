<?php
namespace Squid\MySql\Command\Create;


interface IIndexable
{
	/**
	 * @param string|string[] $name
	 * @param string[] $columns
	 * @return static
	 */
	public function index($name, array $columns = []);
	
	/**
	 * @param string|string[] $name
	 * @param string[] $columns
	 * @return static
	 */
	public function unique($name, array $columns = []);
}