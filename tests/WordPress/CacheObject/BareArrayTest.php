<?php

declare(strict_types=1);

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

		$result = $this->cache->incr($key, 42);
		$this->assertEquals(42, $result);
	}

	/**
	 * @testWith ["youpi"]
	 *			 [""]
	 *			 ["$$dsds1234566!!!!ddddssssahhhhhhhh"]
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

	public function testFillCloseCheck()
	{
		$key = 'prolo';

		$result = $this->cache->incr($key, 10);
		$this->assertEquals(10, $result);
		$result = $this->cache->get($key);
		$this->assertEquals(10, $result);

		$this->cache->close();
		$result = $this->cache->get($key);
		$this->assertNull($result);

		$this->cache->close();
		$result = $this->cache->get($key, $this->cache::default_group_name, false, $success);
		$this->assertNull($result);
		$this->assertFalse($success);
	}

	private $cache;
}
