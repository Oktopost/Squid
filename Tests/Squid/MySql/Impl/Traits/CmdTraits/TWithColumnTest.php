<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use PHPUnit\Framework\TestCase;
use Squid\MySql\Command\IWithColumns;


class TWithColumnTest extends TestCase implements IWithColumns
{
	use TWithColumn;
	
	
	private mixed $lastColumn = null;
	private mixed $lastBind = null; 
	
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->lastColumn = null;
		$this->lastBind = null;
	}
	
	
	/** @noinspection PhpHierarchyChecksInspection */
	public function addColumn(array $columns, array $bind): static
	{
		$this->lastColumn = $columns;
		$this->lastBind = $bind;
		return $this;
	}
	
	
	public function test_column_PassNothing(): void
	{
		$this->column();
		
		
		self::assertEquals([], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_column_SingleColumnPassed(): void
	{
		$this->column('a');
		
		
		self::assertEquals(['a'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_column_ColumnPassedTwice(): void
	{
		$this->column('a', 'b', 'a');
		
		
		self::assertEquals(['a', 'b', 'a'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_column_NumberOfColumnsPassed(): void
	{
		$this->column('a', 'b', 'c');
		
		
		self::assertEquals(['a', 'b', 'c'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	
	public function test_columns_PassNothing(): void
	{
		$this->columns([]);
		
		
		self::assertEquals([], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_columns_PassOneColumn(): void
	{
		$this->columns(['a']);
		
		
		self::assertEquals(['a'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_columns_PassOneColumnAsStirng(): void
	{
		$this->columns('a');
		
		
		self::assertEquals(['a'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_columns_ColumnPassedTwice(): void
	{
		$this->columns(['a', 'b', 'a']);
		
		
		self::assertEquals(['a', 'b', 'a'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_columns_PassNumberOfColumns(): void
	{
		$this->columns(['a', 'b', 'c']);
		
		
		self::assertEquals(['a', 'b', 'c'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_columns_TablePassedToColumnAsString_TableAdded(): void
	{
		$this->columns('a', 't');
		
		
		self::assertEquals(['`t`.`a`'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
	public function test_columns_TablePassed_TableAdded(): void
	{
		$this->columns(['a', 'b'], 't');
		
		
		self::assertEquals(['`t`.`a`', '`t`.`b`'], $this->lastColumn);
		self::assertEquals([], $this->lastBind);
	}
	
}
