<?php
namespace Squid\MySql\Impl\Connectors\Objects;


use Squid\MySql\Connectors\Objects\ID\IIdGenerator;
use Squid\MySql\Connectors\Objects\IIdConnector;
use Squid\MySql\Connectors\Objects\Primary\IInsertHandler;

use Squid\MySql\Impl\Connectors\Objects\Primary\Insert\AutoIncInsertHandler;
use Squid\MySql\Impl\Connectors\Objects\Primary\Insert\GeneratedIdInsertHandler;
use Squid\MySql\Impl\Connectors\Objects\Primary\TIdKey;
use Squid\MySql\Impl\Connectors\Objects\Primary\TIdSave;
use Squid\MySql\Impl\Connectors\Objects\Identity\TIdentityDecorator;
use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;


class IdConnector extends AbstractORMConnector implements IIdConnector
{
	use TIdentityDecorator;
	use TIdSave;
	use TIdKey;
	
	
	/** @var IInsertHandler */
	private $insertHandler = null;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->getIdKey();
	}
	
	
	/**
	 * @param array|mixed $object
	 * @return int|false
	 */
	public function insert($object)
	{
		if ($this->insertHandler)
		{
			return $this->insertHandler
				->setInsertProvider($this->getIdentityConnector())
				->setIdProperty($this->getIdProperty())
				->insert(is_array($object) ? $object : [$object]);
		}
		else
		{
			return $this->getIdentityConnector()->insert($object);
		}
	}

	/**
	 * @param array|mixed $id
	 * @return false|int
	 */
	public function deleteById($id)
	{
		return $this->getGenericObjectConnector()->deleteByFields([$this->getIdField() => $id]);
	}
	
	public function loadById($id)
	{
		if (is_array($id))
		{
			return $this->getGenericObjectConnector()->selectObjectsByFields([$this->getIdField() => $id]);
		}
		else
		{
			return $this->getGenericObjectConnector()->selectObjectByFields([$this->getIdField() => $id]);
		}
	}
	

	/**
	 * @param array|string $column Column name to property name
	 * @param null|string $property
	 * @return static|IdConnector
	 */
	public function setAutoIncrementId($column, ?string $property = null): IdConnector
	{
		$this->setIdKey($column, $property);
		
		$this->insertHandler = new AutoIncInsertHandler();
		$this->insertHandler->setConnector($this->getConnector());
		
		return $this;
	}

	/**
	 * @param array|string $id Column name to property name
	 * @param IIdGenerator $generator
	 * @return IdConnector
	 */
	public function setGeneratedId($id, IIdGenerator $generator): IdConnector
	{
		$this->setIdKey($id);
		
		$this->insertHandler = new GeneratedIdInsertHandler();
		$this->insertHandler
			->setTableName($this->getTableName())
			->setGenerator($generator);
		
		return $this;
	}
}