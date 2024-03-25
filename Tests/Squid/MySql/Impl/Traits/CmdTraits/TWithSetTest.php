<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use PHPUnit\Framework\TestCase;
use Squid\MySql\Command\IWithSet;


class TWithSetTest extends TestCase implements IWithSet
{
	use TWithSet;
	
	
	private array $lastExp = [];
	private array $lastBind = [];
	
	
	private function assertData($expectedExpression, $expectedBind = []): void
	{
		self::assertEquals($expectedExpression, $this->lastExp);
		self::assertEquals($expectedBind, $this->lastBind);
		
		$this->lastExp = [];
		$this->lastBind = [];
	}
	
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->lastExp = [];
		$this->lastBind = [];
	}
	
	protected function _set(string $exp, $bind = []): static
	{
		$this->lastExp[] = $exp;
		$this->lastBind[] = $bind;
		return $this;
	}
	
	
	public function test_returnSelf(): void
	{
		self::assertSame($this, $this->setCase('a', 'b', [], 'b'));
		self::assertSame($this, $this->setCaseExp('a', 'b', [], 'b', 'c'));
	}
	
	
	public function test_set(): void
	{
		$result = $this->set('a');
		$this->assertData(['a=?'], [[false]]);
		self::assertSame($this, $result);
		
		$result = $this->set(['a'], [1]);
		$this->assertData(['a=?'], [[1]]);
		self::assertSame($this, $result);
		
		$result = $this->set(['a', 'b'], [1, 2]);
		$this->assertData(['a=?', 'b=?'], [[1], [2]]);
		self::assertSame($this, $result);
		
		$result = $this->set(['a' => 3, 'b' => 4], [1, 2]);
		$this->assertData(['a=?', 'b=?'], [[3], [4]]);
		self::assertSame($this, $result);
	}
	
	public function test_setExp(): void
	{
		$result = $this->setExp('a', 'b');
		$this->assertData(['a=b'], [[]]);
		self::assertSame($this, $result);
		
		$result = $this->setExp(['a'], ['b']);
		$this->assertData(['a=b'], [false]);
		self::assertSame($this, $result);
		
		$result = $this->setExp(['a'], ['b'], [1]);
		$this->assertData(['a=b'], [1]);
		self::assertSame($this, $result);
		
		$result = $this->setExp(['a', 'b'], ['c', 3], [1, 2]);
		$this->assertData(['a=c', 'b=3'], [1, 2]);
		self::assertSame($this, $result);
		
		$result = $this->setExp(['a' => 'c', 'b' => 3], ['c', 3], [1, 2]);
		$this->assertData(['a=c', 'b=3'], [[], []]);
		self::assertSame($this, $result);
	}
	
	public function test_setCase(): void
	{
		$result = $this->setCase('a', 'b', ['n' => 1]);
		$this->assertData(['a=CASE b WHEN ? THEN ? END'], [['n', 1]]);
		self::assertSame($this, $result);
		
		$result = $this->setCase('a', 'b', ['n' => 1, 'm' => 'f']);
		$this->assertData(['a=CASE b WHEN ? THEN ? WHEN ? THEN ? END'], [['n', 1, 'm', 'f']]);
		self::assertSame($this, $result);
		
		$result = $this->setCase('a', 'b', ['n' => 1], 'el');
		$this->assertData(['a=CASE b WHEN ? THEN ? ELSE ? END'], [['n', 1, 'el']]);
		self::assertSame($this, $result);
	}
	
	public function test_setCaseExp(): void
	{
		$result = $this->setCaseExp('a', 'b', ['n' => 1]);
		$this->assertData(['a=CASE b WHEN n THEN 1 END'], [false]);
		self::assertSame($this, $result);
		
		$result = $this->setCaseExp('a', 'b', ['n' => 1, 'f' => 'g']);
		$this->assertData(['a=CASE b WHEN n THEN 1 WHEN f THEN g END'], [false]);
		self::assertSame($this, $result);
		
		$result = $this->setCaseExp('a', 'b', ['n' => 1], false, ['a']);
		$this->assertData(['a=CASE b WHEN n THEN 1 END'], [['a']]);
		self::assertSame($this, $result);
		
		$result = $this->setCaseExp('a', 'b', ['n' => 1], 'el', ['a']);
		$this->assertData(['a=CASE b WHEN n THEN 1 ELSE el END'], [['a']]);
		self::assertSame($this, $result);
		
		$result = $this->setCaseExp('a', 'b', ['n' => 1], 'el');
		$this->assertData(['a=CASE b WHEN n THEN 1 ELSE el END'], [false]);
		self::assertSame($this, $result);
	}
}
