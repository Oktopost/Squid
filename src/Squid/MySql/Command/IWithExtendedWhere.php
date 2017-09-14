<?php
namespace Squid\MySql\Command;


interface IWithExtendedWhere extends IWithWhere
{
	/**
	 * @param string $field
	 * @param mixed $greater
	 * @param mixed $less
	 * @return IWithExtendedWhere|static
	 */
	public function whereBetween(string $field, $greater, $less): IWithExtendedWhere;

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereNotEqual(string $field, $value): IWithExtendedWhere;

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereLess(string $field, $value): IWithExtendedWhere;

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereLessOrEqualTo(string $field, $value): IWithExtendedWhere;
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereGreater(string $field, $value): IWithExtendedWhere;
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereGreaterOrEqual(string $field, $value): IWithExtendedWhere;
}