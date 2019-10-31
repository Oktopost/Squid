<?php
namespace Squid\MySql\Command;


interface IWithExtendedWhere extends IWithWhere
{
	/**
	 * @param string $field
	 * @param mixed $greater
	 * @param mixed $less
	 * @return static
	 */
	public function whereBetween(string $field, $greater, $less);

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return static
	 */
	public function whereNotEqual(string $field, $value);

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return static
	 */
	public function whereLess(string $field, $value);

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return static
	 */
	public function whereLessOrEqual(string $field, $value);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return static
	 */
	public function whereGreater(string $field, $value);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return static
	 */
	public function whereGreaterOrEqual(string $field, $value);
}