<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary;


use Squid\MySql\Connectors\Objects\IIdentityConnector;
use Squid\MySql\Connectors\Objects\ID\IIdGenerator;

use Squid\MySql\Impl\Connectors\Objects\Identity\TIdentityDecorator;


trait TIdDecorator
{
	use TIdSave;
	use TIdKeyConsumer;
	use TIdentityDecorator;
	
	
	/** @var DecoratedIdConnector */
	private $_idConnector;
	
	
	protected function getBareIdConnector(): DecoratedIdConnector
	{
		if (!$this->_idConnector)
		{
			$this->_idConnector = new DecoratedIdConnector($this);
			$this->_idConnector
				->setGenericObjectConnector($this->getGenericObjectConnector());
		}
		
		return $this->_idConnector;
	}
	
	protected function getIdConnector(): DecoratedIdConnector
	{
		if (!$this->_idConnector)
		{
			$this->_idConnector = new DecoratedIdConnector($this);
			$this->_idConnector
				->setIdKey($this->getIdKey())
				->setGenericObjectConnector($this->getGenericObjectConnector());
		}
		
		return $this->_idConnector;
	}
	
	protected function getIdentityConnector(): IIdentityConnector
	{
		return $this->getIdConnector();
	}
	

	public function deleteById($id)
	{
		return $this->getIdConnector()->deleteById($id);
	}

	public function loadById($id)
	{
		return $this->getIdConnector()->loadById($id);
	}

	/**
	 * @param mixed|array $objects
	 * @return int|false
	 */
	public function save($objects)
	{
		return $this->getIdConnector()->save($objects);
	}
	
	
	/**
	 * @param array|string $column Column name to property name
	 * @param null|string $property
	 * @return static
	 */
	public function setAutoIncrementId($column, ?string $property = null)
	{
		$this->getBareIdConnector()->setAutoIncrementId($column, $property);
		return $this;
	}
	
	/**
	 * @param array|string $column Column name to property name
	 * @param IIdGenerator $generator
	 * @return static
	 */
	public function setGeneratedId($column, IIdGenerator $generator)
	{
		$this->getBareIdConnector()->setGeneratedId($column, $generator);
		return $this;
	}
}