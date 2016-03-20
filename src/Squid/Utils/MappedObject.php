<?php
namespace Squid\Utils;


use \Squid\Base\ICmdSimpleFactory;
use \Squid\Base\Utils\IMappedObject;
use \Squid\Base\Utils\IColumnToPropertyResolve;
use \Squid\Utils\ColumnResolve\PropertyResolve;


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