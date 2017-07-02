<?php
namespace Squid\MySql\Connectors\Generic;


use Squid\Exceptions\SquidException;


/**
 * @mixin ISelectConnector
 */
trait TSelectHelper
{
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|null|false
	 */
	public function oneByField(string $field, $value)
	{
		return $this->oneByFields([$field => $value]);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|null|false
	 */
	public function firstByField(string $field, $value)
	{
		return $this->firstByFields([$field => $value]);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|false
	 */
	public function allByField(string $field, $value)
	{
		return $this->allByFields([$field => $value]);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @param int $limit
	 * @return array|false
	 */
	public function nByField(string $field, $value, int $limit)
	{
		return $this->nByFields([$field => $value], $limit);
	}
	
	/**
	 * @param array $fields
	 * @return array|false
	 */
	public function oneByFields(array $fields)
	{
		$res = $this->nByFields($fields, 2);
		
		if ($res)
		{
			if (count($res) > 1)
				throw new SquidException('More then one row selected!');
				
			return $res[0];
		}
		else
		{
			return ($res === false ? false : null);
		}
	}

	/**
	 * @param array $fields
	 * @return array|null|false
	 */
	public function firstByFields(array $fields)
	{
		$res = $this->nByFields($fields, 1);
		
		if ($res)
		{
			return $res[0];
		}
		else if ($res === false)
		{
			return false;
		}
		else
		{
			return null;
		}
	}
}