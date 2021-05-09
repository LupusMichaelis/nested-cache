<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\ObjectCache;

use PHPUnit\Framework\TestCase;

class ApcuTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->cache = new \LupusMichaelis\NestedCache\ObjectCache\Apcu;
	}

	public function tearDown(): void
	{
		unset($this->cache);
		\apcu_clear_cache();
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\Apcu::class, $this->cache);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testGetNotFound($key)
	{
		$stats = $this->cache->get_stats();
		$this->assertEquals(0, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$this->expectException(\LupusMichaelis\NestedCache\NotFound::class);
		$this->cache->get($key);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testRun($key)
	{
		$stats = $this->cache->get_stats();
		$this->assertEquals(0, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		try { $this->cache->get($key); } catch(\LupusMichaelis\NestedCache\NotFound $e) { }

		$stats = $this->cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$this->cache->set($key, 8956);
		$stats = $this->cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$this->cache->get($key);
		$stats = $this->cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(1, $stats->get_hits());

		$this->cache->delete($key);
		$this->expectException(\LupusMichaelis\NestedCache\NotFound::class);
		$value = $this->cache->get($key);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testCachingCloneableValue($key)
	{
		$object = new Cloneable;

		$this->cache->set($key, $object);
		$cached = $this->cache->get($key);

		$this->assertEquals($object, $cached);
		$this->assertNotSame($object, $cached);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testAddNonClonableObject($key)
	{
		$not_scalar_non_clonable = new \Error('My not clonable error object');

		$this->cache->add($key, $not_scalar_non_clonable);
		$stored_value = $this->cache->get($key);
		$this->assertEquals($not_scalar_non_clonable, $stored_value);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testIncrement($key)
	{
		$value = $this->cache->increment($key, 1);
		$this->assertEquals($value, 1);

		$value = $this->cache->increment($key, 2);
		$this->assertEquals($value, 3);

		$value = $this->cache->decrement($key, 1);
		$this->assertEquals($value, 2);

		$value = $this->cache->decrement($key, 10);
		$this->assertEquals($value, -8);

		$this->cache->set($key, "stop");
		$value = $this->cache->get($key);
		$this->assertEquals($value, "stop");

		$value = $this->cache->increment($key, 11);
		$this->assertEquals($value, 11);

		$this->cache->set($key, "stop");
		$value = $this->cache->get($key);
		$this->assertEquals($value, "stop");

		$value = $this->cache->decrement($key, 9000);
		$this->assertEquals($value, 0);
	}

	public function provideKeys()
	{
		return
			[ [new \LupusMichaelis\NestedCache\Key\Cut(['blog_id' => 1, 'group' => 'group', 'name' => 'target'])]
			];
	}
}

class Cloneable
{
	public $payload;

	public function __construct()
	{
		$this->payload = new \ArrayObject(range(1, 4));
	}

	public function __clone()
	{
		$c = new self;
		$c->payload = clone $this->payload;
	}
};
