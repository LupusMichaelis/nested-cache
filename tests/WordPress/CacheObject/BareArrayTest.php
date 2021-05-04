<?php

namespace LupusMichaelis\NestedCache\Tests\WordPress\CacheObject;

use PHPUnit\Framework\TestCase;

class BareArrayTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->cache = new \LupusMichaelis\NestedCache\WordPress\CacheObject\BareArray;
	}

	public function tearDown(): void
	{
		unset($this->cache);
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\WordPress\CacheObjectInterface::class, $this->cache);
	}

	public function testIncrement()
	{
		$key = 'tatayoyo';
		$result = $this->cache->incr($key);

		$this->assertEquals(1, $result);

		$result = $this->cache->incr($key);
		$this->assertEquals(2, $result);

		$result = $this->cache->incr($key, 10);
		$this->assertEquals(2 + 10, $result);
	}

	private $cache;
}
