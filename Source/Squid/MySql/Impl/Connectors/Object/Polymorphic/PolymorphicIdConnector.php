<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use Squid\MySql\Impl\Connectors\Object\Primary\TIdKey;
use Squid\MySql\Impl\Connectors\Object\Primary\TIdSave;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicIdConnector;


class PolymorphicIdConnector extends PolymorphicIdentityConnector implements IPolymorphicIdConnector
{
	use TIdKey;
	use TIdSave;
	

	/**
	 * @param string|array $id
	 * @return int|false
	 */
	public function deleteById($id)
	{
		return $this->deleteByField($this->getIdField(), $id);
	}
	
	/**
	 * @param string|array $id
	 * @return mixed|array|null|false
	 */
	public function loadById($id)
	{
		if (is_array($id))
		{
			return $this->selectObjectsByFields([$this->getIdField() => $id]);
		}
		else
		{
			return $this->selectObjectByField($this->getIdField(), $id);
		}
	}
}