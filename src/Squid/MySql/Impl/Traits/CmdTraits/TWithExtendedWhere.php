<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Command\ICmdSelect;
use Squid\Exceptions\SquidException;


/**
 * @see \Squid\MySql\Command\IWithExtendedWhere
 */
trait TWithExtendedWhere
{
	/**
	 * @param string $field
	 * @param mixed $greater
	 * @param mixed $less
	 * @return IWithExtendedWhere|static
	 */
	public function whereBetween(string $field, $greater, $less): IWithExtendedWhere
	{
		// TODO:
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereNotEqual(string $field, $value): IWithExtendedWhere
	{
		// TODO:
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereLess(string $field, $value): IWithExtendedWhere
	{
		// TODO:
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereLessOrEqualTo(string $field, $value): IWithExtendedWhere
	{
		// TODO:
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereGreater(string $field, $value): IWithExtendedWhere
	{
		// TODO:
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereGreaterOrEqual(string $field, $value): IWithExtendedWhere
	{
		// TODO:
	}
}