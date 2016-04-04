<?php
namespace Squid\Utils;


use Squid\MySql\ICmdSimpleFactory;
use Squid\MySql\Utils\IMappedObject;
use Squid\MySql\Utils\IColumnToPropertyResolve;
use Squid\Utils\ColumnResolve\PropertyResolve;


abstract class MappedObject implements IMappedObject {
	
	private $table;
	
	/** @var ICmdSimpleFactory */
	private $factory;
	
	
	/**
	 * @param ICmdSimpleFactory $factory
	 * @param string $table
	 */
	public function __construct(ICmdSimpleFactory $factory, $table) {
		$this->factory	= $factory;
		$this->table	= $table;
	}
	
	
	/**
	 * @return IColumnToPropertyResolve
	 */
	public function getColumnMapper() {
		return new PropertyResolve();
	}
	
	
	/**
	 * @return array
	 */
	public abstract function getKeyFields();
	
	/**
	 * @return array
	 */
	public abstract function getValueFields();
}