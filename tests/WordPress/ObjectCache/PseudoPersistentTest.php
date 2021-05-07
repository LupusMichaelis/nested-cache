<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\WordPress\ObjectCache;

use PHPUnit\Framework\TestCase;

class PseudoPersistentTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->cache = new \LupusMichaelis\NestedCache\WordPress\ObjectCache\PseudoPersistent;
	}

	public function tearDown(): void
	{
		$this->cache = null;
	}

	public function testInstance()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\WordPress\ObjectCache\AbstractPersistent::class, $this->cache);
	}

	public function testGroup()
	{
		$this->cache->add_global_groups('yolo');
		$this->cache->add_non_persistent_groups('yolo');

		/// @todo assert internal state of $this->cache after group addition
		$this->assertTrue(true);
	}
}
