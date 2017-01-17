<?php
namespace Squid\MySql\Connectors;


interface ITableConnector
{
	/**
	 * @param string $name
	 * @return static
	 */
	public function setTableName($name);

	/**
	 * @param string $name
	 * @param string $value
	 * @return array|null
	 */
	public function selectRowByField($name, $value);
	
	/**
	 * @param string $name
	 * @param string $value
	 * @return array
	 */
	public function selectAllByField($name, $value);

	/**
	 * @param array $values
	 * @return array|null
	 */
	public function selectRowByFields(array $values);
	
	/**
	 * @param array $values
	 * @return array|null
	 */
	public function selectAllByFields(array $values);
	
	
	/**
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function deleteByField($name, $value);
	
	/**
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function deleteByFields($name, $value);
	
	/**
	 * @param array $field
	 * @return bool
	 */
	public function insertRow(array $field);
}