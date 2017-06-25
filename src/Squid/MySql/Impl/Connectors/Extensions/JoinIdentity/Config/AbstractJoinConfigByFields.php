<?php
namespace Squid\MySql\Impl\Connectors\Extensions\JoinIdentity\Config;


use Squid\MySql\Connectors\Extensions\JoinIdentity\IJoinIdentityConfig;


abstract class AbstractJoinConfigByFields implements IJoinIdentityConfig
{
	private $id;
	private $reference;
	private $dataProperty;
	
	
	public function __construct(string $objectIDProperty, string $dataReferenceProperty, string $dataProperty)
	{
		$this->id			= $objectIDProperty;
		$this->reference	= $dataReferenceProperty;
		$this->dataProperty	= $dataProperty;
	}


	/**
	 * @param mixed|array $object
	 * @return mixed|array
	 */
	public function getData($object)
	{
		if (!is_array($object))
		{
			$data = $this->getData([$object]);
			return $data ? $data[0] : null;
		}
		
		$result = [];
		$prop = $this->dataProperty;
		
		foreach ($object as $item)
		{
			$data = $item->$prop;
			
			if ($data) $result[] = $data;
		}
		
		return $result;
	}

	/**
	 * @param mixed|array $object
	 * @return mixed|array
	 */
	public function getDataIdentifier($object)
	{
		return $object;
	}

	/**
	 * @param mixed $object
	 * @param mixed $data
	 * @return mixed
	 */
	public function combine($object, $data)
	{
		$object->{$this->dataProperty} = $data;
		return $object;
	}

	/**
	 * @param array $objects
	 * @param array $data
	 * @return array
	 */
	public function combineAll(array $objects, array $data): array
	{
		$map = [];
		
		$ref	= $this->reference;
		$id		= $this->id;
		$prop	= $this->dataProperty;
		
		foreach ($data as $datum)
		{
			$map[$datum->$ref] = $datum;
		}
		
		foreach ($objects as $object)
		{
			$currId = $objects->$id;
			
			if (isset($map[$currId]))
			{
				$objects->$prop = $map[$currId];
			}
			else
			{
				$objects->$prop = null;
			}
		}
		
		return $objects;
	}
}