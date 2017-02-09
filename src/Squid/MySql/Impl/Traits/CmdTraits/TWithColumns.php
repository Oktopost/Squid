<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Command\Create\IColumnFactory;
use Squid\MySql\Impl\Command\Create\ColumnFactory;
use Squid\MySql\Impl\Command\Create\ColumnsCollection;


trait TWithColumns
{
	/** @var ColumnsCollection */
	private $columnsList;

	
	/**
	 * @param string $name
	 * @return IColumnFactory
	 */
	public function column($name)
	{
		return new ColumnFactory($this->columnsList, $name);
	}
}