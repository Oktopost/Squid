<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\IObjectConnector;
use Squid\MySql\Connectors\Object\ID\IIDGenerator;
use Squid\MySql\Connectors\Object\CRUD\Generic\IObjectInsert;
use Squid\MySql\Connectors\Object\IIdentityConnector;
use Squid\MySql\Connectors\Object\IQueryConnector;
use Squid\MySql\Connectors\Object\Query\ICmdObjectSelect;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\CRUD\IdentityInsert\SimpleInsert;
use Squid\MySql\Impl\Connectors\Internal\Object\CRUD\IdentityInsert\GeneratorIDInsert;
use Squid\MySql\Impl\Connectors\Internal\Object\CRUD\IdentityInsert\AutoincrementInsert;

use Squid\Exceptions\SquidException;
use Squid\MySql\Impl\Connectors\Object\Query\CmdObjectSelect;


class SimpleConnector extends AbstractORMConnector implements IIdentityConnector, IQueryConnector
{
	private $idFiled;
	private $idProperty;
	
	/** @var IObjectConnector */
	private $objectConnector;
	
	/** @var IObjectInsert */
	private $insertHandler;


	private function setID(string $name, ?string $fieldName = null)
	{
		$this->idFiled = ($fieldName ?: $name);
		$this->idProperty = $name;
	}
	
	
	/**
	 * @param string $name
	 * @param string|null $fieldName
	 * @return static
	 */
	public function setIDProperty(string $name, ?string $fieldName = null)
	{
		$this->setID($name, $fieldName);
		$this->insertHandler = new SimpleInsert($this);
		return $this;
	}

	/**
	 * @param string $name
	 * @param string|null $fieldName
	 * @return static
	 */
	public function setAutoincrementID(string $name, ?string $fieldName = null)
	{
		$this->setIDProperty($name, $fieldName);
		$this->insertHandler = new AutoincrementInsert($this, $name);
		return $this;
	}

	/**
	 * @param IIDGenerator $generator
	 * @return static
	 */
	public function setIDGenerator(IIDGenerator $generator)
	{
		$this->insertHandler = new GeneratorIDInsert($this, $generator);
		return $this;
	}
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		$idProp = $this->idProperty;
		
		if (!is_array($object))
			return $this->deleteById($object->$idProp);
		
		$ids = [];
		
		foreach ($object as $item)
		{
			$ids[] = $item->$idProp;
		}
		
		return $this->deleteById($ids);
	}

	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return int|false
	 */
	public function insert($object, bool $ignore = false)
	{
		if (is_null($this->insertHandler))
			throw new SquidException('ID field ');
		
		return $this->insertHandler->insert($object, $ignore);
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		return $this->getGenericConnector()->update($object, [$this->idFiled]);
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		return $this->getGenericConnector()->upsertByKeys($object, [$this->idFiled]);
	}

	/**
	 * @param mixed $id
	 * @return mixed|null|false
	 */
	public function load($id)
	{
		$where = [$this->idFiled => $id];
		
		if (is_array($id))
		{
			return $this->getGenericConnector()->selectAllByFields($where);
		}
		else
		{
			return $this->getGenericConnector()->selectOneByFields($where);
		}
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function save($object)
	{
		if (is_array($object))
		{
			$updateResult = 0;
			$insertResult = 0;
			
			$insert = [];
			$update = [];
			
			foreach ($object as $item)
			{
				if ($item->{$this->idProperty})
				{
					$update[] = $item;
				}
				else
				{
					$insert[] = $item;
				}
			}
			
			if ($insert) 
			{
				$insertResult = $this->insert($object);
			}
			
			if ($update)
			{
				$updateResult = $this->upsert($object);
			}
			
			return ($insertResult !== false && $updateResult !== false ?
				$updateResult + $insertResult : 
				false);
		}
		else if (!$object->{$this->idProperty})
		{
			return $this->insert($object);
		}
		else
		{
			return $this->update($object);
		}
	}
	
	/**
	 * @param mixed $id
	 * @return mixed|false
	 */
	public function deleteById($id)
	{
		return $this->getTable()
			->delete()
			->byField($this->idFiled, $id)
			->executeDml(true);
	}
	
	public function query(): ICmdObjectSelect
	{
		$query = new CmdObjectSelect($this->getObjectMap());
		return $query->setConnector($this->getConnector())->from($this->getTableName());
	}
	
	public function getGenericConnector(): IObjectConnector
	{
		if (!$this->objectConnector)
		{
			$this->objectConnector = new ObjectConnector($this);
		}
		
		return $this->objectConnector; 
	}
}