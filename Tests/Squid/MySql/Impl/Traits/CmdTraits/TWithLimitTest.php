<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use PHPUnit\Framework\TestCase;

use Squid\OrderBy;
use Squid\MySql\Command\IWithLimit;


class TWithLimitTest extends TestCase implements IWithLimit
{
	use TWithLimit;
	
	
	private mixed $lastFrom;
	private mixed $lastCount;
	private mixed $lastOrder;
	
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->lastFrom = null;
		$this->lastCount = null;
		$this->lastOrder = null;
	}
	
	
	public function limit($from, $count): static
	{
		$this->lastFrom = $from;
		$this->lastCount = $count;
		
		return $this;
	}
	
 	public function _orderBy(array $expressions): static
	{
		$this->lastOrder = $expressions; 
		return $this;
	}
	
	
	public function test_returnSelf(): void
	{
		self::assertSame($this, $this->limitBy(0));
		self::assertSame($this, $this->page(0, 0));
		self::assertSame($this, $this->orderBy('a'));
		self::assertSame($this, $this->orderByAsc('a'));
		self::assertSame($this, $this->orderByDesc('a'));
	}
	
	
	public function test_limitBy_zero(): void
	{
		$this->limitBy(0);
		
		
		self::assertEquals(0,	$this->lastFrom);
		self::assertEquals(0,	$this->lastCount);
	}
	
	public function test_limitBy(): void
	{
		$this->limitBy(25);
		
		
		self::assertEquals(0,	$this->lastFrom);
		self::assertEquals(25,	$this->lastCount);
	}
	
	
	public function test_page_ZeroValues(): void
	{
		$this->page(0, 0);
		
		
		self::assertEquals(0,	$this->lastFrom);
		self::assertEquals(0,	$this->lastCount);
	}
	
	public function test_page_zeroOffset(): void
	{
		$this->page(0, 13);
		
		
		self::assertEquals(0,	$this->lastFrom);
		self::assertEquals(13,	$this->lastCount);
	}
	
	public function test_page(): void
	{
		$this->page(2, 13);
		
		
		self::assertEquals(26,	$this->lastFrom);
		self::assertEquals(13,	$this->lastCount);
	}
	
	
	public function test_orderBy_ColumnAsString(): void
	{
		$this->orderBy('a');
		
		
		self::assertEquals(['a'],	$this->lastOrder);
	}
	
	public function test_orderBy_ColumnAsArray(): void
	{
		$this->orderBy(['a']);
		
		
		self::assertEquals(['a'],	$this->lastOrder);
	}
	
	public function test_orderBy_NumberOfColumns(): void
	{
		$this->orderBy(['a', 'b', 'c']);
		
		
		self::assertEquals(['a', 'b', 'c'],	$this->lastOrder);
	}
	
	public function test_orderBy_WithOrderSet(): void
	{
		$this->orderBy(['a', 'b', 'c'], OrderBy::ASC);
		self::assertEquals(['a', 'b', 'c'],	$this->lastOrder);
		
		$this->orderBy(['a', 'b', 'c'], OrderBy::DESC);
		self::assertEquals(['a DESC', 'b DESC', 'c DESC'],	$this->lastOrder);
		
		$this->orderBy('a', OrderBy::ASC);
		self::assertEquals(['a'], $this->lastOrder);
		
		$this->orderBy('a', OrderBy::DESC);
		self::assertEquals(['a DESC'], $this->lastOrder);
	}
}
