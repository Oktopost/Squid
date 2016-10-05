<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\Connectors\IMySqlAutoIncrementConnector;

use Objection\LiteObject;


class MySqlAutoIncrementConnector extends MySqlObjectConnector implements IMySqlAutoIncrementConnector
{
	private $idField = 'Id';
	private $idFieldArray = ['Id'];
	
	
	/**
	 * @param LiteObject $object
	 * @return bool
	 */
	private function insertNewObject(LiteObject $object)
	{
		if (!$this->insert($object, [$this->idField]))
			return false;
		
		$lastId = $this->getConnector()->controller()->lastId();
		
		if ($lastId !== false)
		{
			$object->{$this->idField} = $lastId;
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * @param string $field
	 * @return static
	 */
	public function setIdField($field = 'Id')
	{
		$this->idField = $field;
		$this->idFieldArray = [$field];
		return $this;
	}
	
	/**
	 * @param LiteObject $object
	 * @return bool
	 */
	public function save(LiteObject $object)
	{
		if (is_null($object->{$this->idField}))
		{
			return $this->insertNewObject($object);
		}
		else
		{
			return $this->update($object);
		}
	}
	
	/**
	 * @param LiteObject $object
	 * @return bool
	 */
	public function update(LiteObject $object)
	{
		$data = $this->getMapper()->getArray($object);
		
		foreach ($this->idFieldArray as $idField)
		{
			unset($data[$idField]);
		}
		
		return $this->updateByFields(
			$data,
			[ $this->idField => $object->{$this->idField} ]);
	}
	
	/**
	 * @param int $id
	 * @return LiteObject|null
	 */
	public function load($id)
	{
		return $this->loadOneByField($this->idField, $id);
	}
	
	/**
	 * @param int $id
	 * @return bool
	 */
	public function delete($id)
	{
		return $this->deleteByField($this->idField, $id);
	}
}