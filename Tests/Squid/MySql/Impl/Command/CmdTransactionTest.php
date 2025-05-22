<?php
namespace Squid\MySql\Impl\Command;


use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Squid\Exceptions\AlreadyInTransactionException;
use Squid\MySql\Connection\IMySqlConnection;


class CmdTransactionTest extends TestCase
{
	/**
	 * @return IMySqlConnection|MockObject
	 */
	private function mockConnection(): IMySqlConnection|MockObject
	{
		/** @var IMySqlConnection $mock */
		$mock = $this->getMockBuilder(IMySqlConnection::class)->getMock();
		return $mock;
	}

	/**
	 * @param bool $result
	 * @return MockObject|IMySqlConnection
	 */
	private function mockConnectionWithResult($result = true)
	{
		$mock = $this->mockConnection();
		$mock->method('execute')->willReturn($result);
		return $mock;
	}

	/**
	 * @param string $command
	 * @param bool $result
	 * @return MockObject|IMySqlConnection
	 */
	private function mockConnectionExpect(string $command, $result = true)
	{
		$mock = $this->mockConnection();
		$mock->expects($this->once())->method('execute')->with($command)->willReturn($result);
		return $mock;
	}

	private function getSubject($conn = null, $result = true)
	{
		$transaction = new CmdTransaction();

		if (is_string($conn))
		{
			$transaction->setConnection($this->mockConnectionExpect($conn, $result));
		}
		else if ($conn)
		{
			$transaction->setConnection($conn);
		}
		else
		{
			$transaction->setConnection($this->mockConnectionWithResult($result));
		}

		return $transaction;
	}


	public function test_startTransaction_CommandExecuted()
	{
		$transaction = $this->getSubject('START TRANSACTION');
		$transaction->startTransaction();
	}

	public function test_startTransaction_CalledTwice_ErrorThrown()
	{
		self::expectException(AlreadyInTransactionException::class);
		$transaction = $this->getSubject();
		$transaction->startTransaction();
		$transaction->startTransaction();
	}


	public function test_commit_CommandExecuted()
	{
		$conn = $this->mockConnection();
		$conn->expects($this->exactly(2))->method('execute')
			->willReturnCallback(function($query = null) {
				static $callCount = 0;
				$callCount++;

				if ($callCount === 2) {
					$this->assertEquals('COMMIT', $query);
				}

				return true;
			});
		$subject = $this->getSubject($conn);

		$subject->startTransaction();
		$subject->commit();
	}

	public function test_commit_NotInTransaction_ErrorThrown()
	{
		self::expectException(\Squid\Exceptions\NotInTransactionException::class);
		$transaction = $this->getSubject();
		$transaction->commit();
	}


	public function test_rollback_NotInTransaction_ReturnTrue()
	{
		$transaction = $this->getSubject();
		$this->assertTrue($transaction->rollback());
	}

	public function test_rollback_NotInTransaction_CommandExecuted()
	{
		$conn = $this->mockConnection();
		$conn->expects($this->once())->method('execute')->with('ROLLBACK');
		$subject = $this->getSubject($conn);

		$subject->rollback();
	}

	public function test_rollback_AlwaysReturnTrue()
	{
		$conn = $this->mockConnection();
		$conn->method('execute')->willReturn(false);
		$subject = $this->getSubject($conn);

		$this->assertTrue($subject->rollback());
	}

	public function test_rollback_CommandExecuted()
	{
		$conn = $this->mockConnection();
		$conn->expects($this->exactly(2))->method('execute')
			->willReturnCallback(function($query = null) {
				static $callCount = 0;
				$callCount++;

				if ($callCount === 2) {
					$this->assertEquals('ROLLBACK', $query);
				}

				return true;
			});
		$subject = $this->getSubject($conn);

		$subject->startTransaction();
		$subject->rollback();
	}


	public function test_isInTransaction_ByDefault_False()
	{
		$transaction = $this->getSubject();
		$this->assertFalse($transaction->isInTransaction());
	}

	public function test_isInTransaction_AfterNewTransaction_True()
	{
		$transaction = $this->getSubject();
		$transaction->startTransaction();

		$this->assertTrue($transaction->isInTransaction());
	}

	public function test_isInTransaction_AfterCommit_False()
	{
		$transaction = $this->getSubject();

		$transaction->startTransaction();
		$transaction->commit();

		$this->assertFalse($transaction->isInTransaction());
	}

	public function test_isInTransaction_AfterRollback_False()
	{
		$transaction = $this->getSubject();

		$transaction->startTransaction();
		$transaction->rollback();

		$this->assertFalse($transaction->isInTransaction());
	}

	public function test_isInTransaction_ExceptionInCommit_False()
	{
		$conn = $this->mockConnection();
		$conn->method('execute')->willReturnCallback(
			function ($cmd)
			{
				if ($cmd === 'COMMIT') throw new \Exception();

				return true;
			});

		$transaction = $this->getSubject($conn);
		$transaction->startTransaction();

		try
		{
			$transaction->commit();
			$this->fail('No Exception thrown');
		}
		catch (\Exception $e) {}

		$this->assertFalse($transaction->isInTransaction());
	}

	public function test_isInTransaction_ExceptionInRollback_False()
	{
		$conn = $this->mockConnection();
		$conn->method('execute')->willReturnCallback(
			function ($cmd)
			{
				if ($cmd === 'ROLLBACK') throw new \Exception();

				return true;
			});

		$transaction = $this->getSubject($conn);
		$transaction->startTransaction();

		try
		{
			$transaction->rollback();
			$this->fail('No Exception thrown');
		}
		catch (\Exception $e) {}

		$this->assertFalse($transaction->isInTransaction());
	}

	public function test_isInTransaction_ExceptionBecauseStartTransactionCalledTwice_True()
	{
		$transaction = $this->getSubject();
		$transaction->startTransaction();

		try
		{
			$transaction->startTransaction();
			$this->fail('No Exception thrown');
		}
		catch (\Exception $e) {}

		$this->assertTrue($transaction->isInTransaction());
	}

}
