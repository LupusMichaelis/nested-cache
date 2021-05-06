<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\WordPress\ObjectCache;

use PHPUnit\Framework\TestCase;

class AbstractPersistentTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->cache = $this->getMockForAbstractClass(\LupusMichaelis\NestedCache\WordPress\ObjectCache\AbstractPersistent::class);
	}

	public function tearDown(): void
	{
		$this->cache = null;
	}

	public function testInstance()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\WordPress\ObjectCache\AbstractPersistent::class, $this->cache);
	}
}
