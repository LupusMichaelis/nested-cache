<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests;

use PHPUnit\Framework\TestCase;

class LoggerTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->error_reporting = error_reporting(-1);
		$this->logger = new \LupusMichaelis\NestedCache\Logger;
		$this->error_log = ini_set('error_log', 'php://output');
	}

	public function tearDown(): void
	{
		error_reporting($this->error_reporting);
		ini_set('error_log', $this->error_log);
		$this->logger = null;
	}

	public function testSuccessInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\LoggerInterface::class, $this->logger);
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\Logger::class, $this->logger);
	}

	public function testWriteLog()
	{
		$this->expectNotice();
		$this->expectNoticeMessage('Ma super info');
		$this->logger->info('Ma super info');

		$this->expectNotice();
		$this->expectNoticeMessage('Ma super info roupoudou');
		$this->logger->info('Ma super info %s', 'roupoudou');
	}

	public function testLocalizedLog()
	{
		$this->expectNotice();
		$this->expectNoticeMessage('afile:42:Ma super info');
		$this->logger
			->at('afile', 42)
			->info('Ma super info');
	}

	public function testError()
	{
		$this->expectError();
		$this->expectErrorMessage('in flammes');
		$this->logger
			->error('in flammes');
	}

	public function testLocalizedError()
	{
		$this->expectError();
		$this->expectErrorMessage('cursed.php:821:in flammes');
		$this->logger
			->at('cursed.php', 821)
			->error('in flammes');
	}

	public function testWarning()
	{
		$this->expectWarning();
		$this->expectWarningMessage('beep beep');
		$this->logger
			->warning('beep beep');
	}

	public function testLocalizedWarning()
	{
		$this->expectWarning();
		$this->expectWarningMessage('bumper.php:759:pinpon pinpon');
		$this->logger
			->at('bumper.php', 759)
			->warning('pinpon pinpon');
	}

	/**
	 * @skip
	 */
	public function testErrorLog()
	{
		$this->markTestSkipped('Can\'t figure how to test error_log call');

		$this->expectOutputString('in the lake');
		$this->logger
			->debug('in the lake');
	}

	/**
	 * @skip
	 */
	public function testLocalizedErrorLog()
	{
		$this->markTestSkipped('Can\'t figure how to test error_log call');

		$this->expectOutputString('knot.php:32:in the sea');
		$this->logger
			->at('knot.php', 32)
			->debug('in the sea');
	}
}
