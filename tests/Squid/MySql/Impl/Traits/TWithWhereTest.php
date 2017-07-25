<?php
namespace Squid\MySql\Impl\Traits;


use PHPUnit\Framework\TestCase;

use Squid\Exceptions\SquidException;
use Squid\MySql;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;


class TWithWhereTest extends TestCase
{
	use TWithWhere;
	
	
	private $field = null;
	private $value = null;

	
	private function isMultiDimensionalArray($value): bool
	{
		return is_array($value) && isset($value[0]) && is_array($value[0]);
	}
	
	private function getExpectedString(array $field): string
	{
		return '(' . implode(',', array_pad([], count($field), '?')) . ')';
	}
	
	private function checkValue($bind, $exp): void
	{
		if ($this->value instanceof ICmdSelect)
		{
			self::assertContains($this->value->assemble(), $exp);
			self::assertEquals($this->value->bind(), $bind);
		}
		else if ($this->isMultiDimensionalArray($this->value))
		{
			self::assertEquals(array_reduce($this->value, 'array_merge', []), $bind);
		}
		else
		{
			self::assertEquals($this->value, $bind);
		}
	}
	
	private function checkField($exp): void
	{
		if (is_array($this->field))
		{
			self::assertContains(implode(',', $this->field), $exp);
			
			if (!$this->value instanceof ICmdSelect)
			{
				self::assertEquals(sizeof($this->field), substr_count($exp, $this->getExpectedString($this->field)));
			}
		}
		else
		{
			self::assertContains($this->field, $exp);
		}
	}
	
	
	/**
	 * It ss called inside the trait
	 */
	public function where($exp, $bind = false): void
	{
		$this->checkValue($bind, $exp);
		$this->checkField($exp);
	}


	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_WhereIn_PassEmptyValue()
	{
		$this->field = 'field';
		$this->value = null;
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_WhereIn_PassStringFieldAndArrayValues_GotStringAndBind()
	{
		$this->field = 'field';
		$this->value = [1,2];
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_WhereIn_PassArrayField_AndPlainArrayValues_GotStringAndBind()
	{
		$this->field = ['field1', 'field2'];
		$this->value = [1,2];
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_WhereIn_PassArrayField_AndMultidimensionalArrayValues_GotStringAndBind()
	{
		$this->field = ['field1', 'field2'];
		$this->value = [[1,2], [1,4]];
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_WhereIn_PassStringField_AndISelectValue_GotStringAndBind()
	{
		$this->field = 'field';
		$this->value = (new MySql\Impl\Command\CmdSelect())->from('TestTable')->where('A', 1);
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_WhereIN_PassArrayField_AndISelectValue_GotStringAndBind()
	{
		$this->field = ['field1', 'field2'];
		$this->value = (new MySql\Impl\Command\CmdSelect())->from('TestTable')->where('A', 1);
		
		$this->whereIn($this->field, $this->value);
	}
}