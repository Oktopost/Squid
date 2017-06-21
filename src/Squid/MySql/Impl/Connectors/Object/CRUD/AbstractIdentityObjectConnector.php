<?php
namespace Squid\MySql\Impl\Connectors\Object\CRUD;


use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;
use Squid\MySql\Connectors\Object\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\IORMConnector;
use Squid\MySql\Impl\Connectors\Object\ORMConnector;


abstract class AbstractIdentityObjectConnector extends ORMConnector implements IIdentifiedObjectConnector
{
	private $idFiled;
	private $idProperty;
	
	/** @var IGenericObjectConnector */
	private $objectConnector;


	protected function getIDField(): string
	{
		return $this->idFiled;
	}
	
	protected function getIDProperty(): string
	{
		return $this->idProperty;
	}
	
	protected function getGenericConnector(): IGenericObjectConnector
	{
		if (!$this->objectConnector)
			$this->objectConnector = new GenericObjectConnector($this);
		
		return $this->objectConnector; 
	}
	
	
	/**
	 * @param IORMConnector|null $parent
	 */
	public function __construct(IORMConnector $parent = null)
	{
		parent::__construct($parent);
	}
	

	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public abstract function insert($object, bool $ignore = false);


	/**
	 * @param string $field
	 * @param null|string $property
	 * @return AbstractIdentityObjectConnector
	 */
	public function setIdField(string $field, ?string $property = null): AbstractIdentityObjectConnector
	{
		$this->idFiled = $field;
		$this->idProperty = ($property ?: $field);
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
}