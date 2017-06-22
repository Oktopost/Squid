<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\ID\IIDGenerator;
use Squid\MySql\Connectors\Object\CRUD\IObjectInsert;
use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;
use Squid\MySql\Connectors\Object\Selector\ICmdObjectSelect;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\IdentityInsert\GeneratorIDInsert;
use Squid\MySql\Impl\Connectors\Internal\Object\CRUD\IdentityInsert\AutoincrementInsert;


class SimpleObjectConnector extends AbstractORMConnector implements IIdentifiedObjectConnector
{
	private $idFiled;
	private $idProperty;
	
	/** @var IGenericObjectConnector */
	private $objectConnector;
	
	/** @var IObjectInsert */
	private $insertHandler;


	/**
	 * @param string $name
	 * @param string|null $fieldName
	 * @return static
	 */
	public function setIDProperty(string $name, ?string $fieldName = null)
	{
		$this->idFiled = ($fieldName ?: $name);
		$this->idProperty = $name;
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
		return $this->deleteById($object->{$this->idProperty});
	}

	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false)
	{
		return $this->insertHandler->insert($object, $ignore);
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		return $this->getGenericConnector()->upsertByKeys($object, [$this->idFiled]);
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
			->executeDml();
	}
	
	/**
	 * @return IGenericObjectConnector
	 */
	public function getGenericConnector(): IGenericObjectConnector
	{
		if (!$this->objectConnector)
			$this->objectConnector = new GenericObjectConnector($this);
		
		return $this->objectConnector; 
	}
	
	/**
	 * @return ICmdObjectSelect
	 */
	public function query(): ICmdObjectSelect
	{
		$query = new CmdObjectSelect($this->getObjectMap());
		return $query->setConnector($this->getConnector());
	}
}