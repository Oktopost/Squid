<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Command\ICmdSelect;
use Squid\Exceptions\SquidException;
use Squid\Utils\EmptyWhereInHandler;


/**
 * Implements calculation behavior for the IWithWhere interface. Only method that is not implemented, 
 * is where. Where must be implemented by the using class.
 * @method where(string $exp, $bind = [])
 * @see \Squid\MySql\Command\IWithSet
 */
trait TWithWhere
{
	/**
	 * @inheritdoc
	 */
	private function byFieldsNum($fields, $values)
	{
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++)
		{
			$this->byField($fields[$i], $values[$i]);
		}
		
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	private function byFieldsAssoc($fields)
	{
		$self = $this;
		
		foreach ($fields as $field => $value)
		{
			$self = $this->byField($field, $value);
		}
		
		/** @noinspection PhpUndefinedVariableInspection */
		return $self;
	}
	
	private function isConvertableToPlainArray($array): bool 
	{
		return is_array($array) && isset($array[0]) && is_array($array[0]);
	}
	
	private function prepareFiller(array $field): string 
	{
		return '(' . implode(',', array_pad([], count($field), '?')) . ')';
	}
	
	
	abstract protected function getVersion(): string;
	
	
	/**
	 * @param string $field
	 * @param array|string $value If array, IN used instead
	 * @return static
	 */
	public function byField($field, $value) 
	{
		if (is_null($value)) return $this->where("ISNULL($field)");
		else if (is_array($value)) return $this->whereIn($field, $value);
		
		return $this->where("$field=?", $value);
	}
	
	/**
	 * @inheritdoc
	 */
	public function byFields($fields, $values = null) 
	{
		if (isset($fields[0])) return $this->byFieldsNum($fields, $values);
		
		return $this->byFieldsAssoc($fields);
	}
	
	/**
	 * @param string|string[] $field
	 * @param ICmdSelect|array $values
	 * @param bool $negate
	 */
	public function whereIn($field, $values, $negate = false) 
	{
		if (!$values)
		{
			/** @noinspection PhpParamsInspection */
			EmptyWhereInHandler::handle($field, $this);
			return $this;
		}
		
		if ($this->getVersion() < '5.7' && is_array($field) && !($values instanceof ICmdSelect))
		{
			$singleSet = '(' . implode(' = ? AND ', $field). ' = ?' . ')';
			$expression = '(' . implode(' OR ', array_fill(0, count($values), $singleSet)) . ')';
			
			if ($negate)
			{
				$expression = "NOT $expression";
			}
			
			return $this->where($expression, array_merge(...$values));
		}
		else
		{
			/** @var ICmdSelect|array $values */
			if ($values instanceof ICmdSelect) 
			{
				$in = $values->assemble();
				$values = $values->bind();
			}
			else 
			{
				$filler = is_array($field) ? $this->prepareFiller($field)  : '?';
				$in = implode(',', array_pad([], count($values), $filler));
			}
			
			$statement = ($negate ? 'NOT IN' : 'IN');
			
			if (is_array($field))
			{
				$field = '(' . implode(',', $field) . ')';
				
				if ($this->isConvertableToPlainArray($values))
				{
					$values = array_merge(...$values);
				}
			}
			
			return $this->where("$field $statement ($in)", $values);
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function whereNotIn($field, $values) 
	{
		return $this->whereIn($field, $values, true);
	}
	
	/**
	 * @inheritdoc
	 */
	public function whereExists(ICmdSelect $select, $negate = false) 
	{
		$in = $select->assemble();
		$statement = ($negate ? 'NOT EXISTS' : 'EXISTS');
		
		return $this->where("$statement ($in)", $select->bind());
	}
	
	/**
	 * @inheritdoc
	 */
	public function whereNotExists(ICmdSelect $select) 
	{
		return $this->whereExists($select, true);
	}
}