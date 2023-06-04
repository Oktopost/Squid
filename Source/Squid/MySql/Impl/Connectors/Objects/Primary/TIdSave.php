<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary;


trait TIdSave
{
	protected abstract function getIdProperty(): string;
	public abstract function insert($object);
	public abstract function upsert($object);
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function save($object)
	{
		$prop = $this->getIdProperty();
		
		if (is_array($object))
		{
			$insert = [];
			$update = [];
			$insertCount = 0;
			$updateCount = 0;
			
			foreach ($object as $item)
			{
				if ($item->$prop)
					$update[] = $item;
				else
					$insert[] = $item;
			}
			
			if ($insert)
				$insertCount = $this->insert($insert);
			
			if ($insertCount !== false && $update)
				$updateCount = $this->upsert($update);
			
			return ($updateCount === false || $insertCount === false ? false : $insertCount + $updateCount);
		}
		else if ($object->$prop)
		{
			return $this->upsert($object);
		}
		else
		{
			return $this->insert($object);
		}
	}
}