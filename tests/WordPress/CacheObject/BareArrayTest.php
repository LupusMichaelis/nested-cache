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

		$result = $this->cache->incr($key, null);
		$this->assertEquals(12, $result);

		$result = $this->cache->incr($key, 'trotro');
		$this->assertEquals(12, $result);
	}

	public function testDecrementWithFloor()
	{
		$key = 'tatayoyo';
		$result = $this->cache->decr($key);

		$this->assertEquals(0, $result);

		$result = $this->cache->decr($key);
		$this->assertEquals(0, $result);

		$result = $this->cache->decr($key, 10);
		$this->assertEquals(0, $result);

		$result = $this->cache->incr($key, 10);
		$this->assertEquals(10, $result);

		$result = $this->cache->decr($key);
		$this->assertEquals(9, $result);

		$result = $this->cache->decr($key, 5);
		$this->assertEquals(4, $result);

		$result = $this->cache->decr($key, 5);
		$this->assertEquals(0, $result);

		$result = $this->cache->decr($key, 'trotro');
		$this->assertEquals(0, $result);

		$result = $this->cache->incr($key, 42);
		$this->assertEquals(42, $result);

		$result = $this->cache->decr($key, 'trotro');
		$this->assertEquals(42, $result);
	}

	/**
	 *	@testWith ["me"]
	 *			  [null]
	 *			  [42]
	 */
	public function testInrementDecrementOnGroups($group)
	{
		$key = 'roudoudou';

		$result = $this->cache->incr($key, 10, $group);
		$this->assertEquals(10, $result);

		$result = $this->cache->incr($key, 10, $group);
		$this->assertEquals(10 + 10, $result);

		$result = $this->cache->decr($key, 1, $group);
		$this->assertEquals(10 + 10 - 1, $result);
	}

	public function testDelete()
	{
		$key = 'yolo';

		$result = $this->cache->incr($key, 10);
		$this->assertEquals(10, $result);

		$result = $this->cache->delete($key);
		$this->assertTrue($result);

		$result = $this->cache->incr($key, 21);
		$this->assertEquals(21, $result);
	}

	private $cache;
}
