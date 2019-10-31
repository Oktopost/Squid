<?php
namespace Squid\MySql\Impl\Traits;


use PHPUnit\Framework\TestCase;

use Squid\MySql;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;
use Squid\Utils\EmptyWhereInHandler;


class TWithWhereTest extends TestCase implements MySql\Command\IWithWhere
{
	use TWithWhere;
	
	
	private $field = null;
	private $value = null;
	
	private $version = '5.7';

	
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
	
	private function invokeWhereInWithEmptyValue(string $field): void
	{
		try
		{
			$this->whereIn('hello', []);
		}
		finally
		{
			$r = new \ReflectionClass(EmptyWhereInHandler::class);
			$p = $r->getProperty('handler');
			$p->setAccessible(true);
			$p->setValue(null, null);
		}
	}
	
	
	protected function getVersion(): string
	{
		return $this->version;
	}
	
	
	public function where($exp, $bind = []): void
	{
		$this->checkValue($bind, $exp);
		$this->checkField($exp);
	}


	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_whereIn_PassEmptyValue()
	{
		$this->field = 'field';
		$this->value = null;
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_whereIn_PassStringFieldAndArrayValues_GotStringAndBind()
	{
		$this->field = 'field';
		$this->value = [1,2];
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_whereIn_PassArrayField_AndPlainArrayValues_GotStringAndBind()
	{
		$this->field = ['field1', 'field2'];
		$this->value = [1,2];
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_whereIn_PassArrayField_AndMultidimensionalArrayValues_GotStringAndBind()
	{
		$this->field = ['field1', 'field2'];
		$this->value = [[1,2], [1,4]];
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_whereIn_PassStringField_AndISelectValue_GotStringAndBind()
	{
		$this->field = 'field';
		$this->value = (new MySql\Impl\Command\CmdSelect())->from('TestTable')->where('A', 1);
		
		$this->whereIn($this->field, $this->value);
	}
	
	public function test_whereIn_PassArrayField_AndISelectValue_GotStringAndBind()
	{
		$this->field = ['field1', 'field2'];
		$this->value = (new MySql\Impl\Command\CmdSelect())->from('TestTable')->where('A', 1);
		
		$this->whereIn($this->field, $this->value);
	}
	
	
	public function test_whereIn_PassEmptyArray_HandlerInvoked()
	{
		$field = null;
		$where = null;
		
		EmptyWhereInHandler::set(
			function($a, $b) 
				use (&$field, &$where)
			{
				$field = $a;
				$where = $b;
			});
		
		
		$this->invokeWhereInWithEmptyValue('hello');
		
		
		self::assertEquals('hello', $field);
		self::assertSame($this, $where);
	}
	
	protected function getConn(): ?IMySqlConnection
	{
		return null;
	}
}