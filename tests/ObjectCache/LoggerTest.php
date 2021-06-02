<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\ObjectCache;

use PHPUnit\Framework\TestCase;

class LoggerTest
	extends TestCase
{
	public function setUp(): void
	{
		// @fixme we shut up error_log (which output in php://stdout instead of a buffered
		//		  output
		$this->error_log = ini_set('error_log', '/dev/null');

		$this->actual = new \LupusMichaelis\NestedCache\ObjectCache\Apcu;
		$this->logged_cache = new \LupusMichaelis\NestedCache\ObjectCache\Logger($this->actual);
	}

	public function tearDown(): void
	{
		unset($this->logged_cache);
		\apcu_clear_cache();
		ini_set('error_log', $this->error_log);

	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\Logger::class, $this->logged_cache);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testGet($key)
	{
		$stats = $this->logged_cache->get_stats();
		$this->assertEquals(0, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$this->expectException(\LupusMichaelis\NestedCache\NotFound::class);
		$this->logged_cache->get($key);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testRun($key)
	{
		$stats = $this->logged_cache->get_stats();
		$this->assertEquals(0, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		try { $this->logged_cache->get($key); } catch(\LupusMichaelis\NestedCache\NotFound $e) { }

		$stats = $this->logged_cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$this->logged_cache->set($key, 8956, 0);
		$stats = $this->logged_cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$this->logged_cache->get($key);
		$stats = $this->logged_cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(1, $stats->get_hits());

		$this->logged_cache->delete($key);
		$this->expectException(\LupusMichaelis\NestedCache\NotFound::class);
		$value = $this->logged_cache->get($key);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testCachingCloneableValue($key)
	{
		$object = new Cloneable;

		$this->logged_cache->set($key, $object, 0);
		$cached = $this->logged_cache->get($key);

		$this->assertEquals($object, $cached);
		$this->assertNotSame($object, $cached);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testAddNonClonableObject($key)
	{
		$not_scalar_non_clonable = new \Error('My not clonable error object');

		$this->logged_cache->add($key, $not_scalar_non_clonable, 0);
		$stored_value = $this->logged_cache->get($key);
		$this->assertEquals($not_scalar_non_clonable, $stored_value);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testIncrement($key)
	{
		$value = $this->logged_cache->increment($key, 1);
		$this->assertEquals($value, 1);

		$value = $this->logged_cache->increment($key, 2);
		$this->assertEquals($value, 3);

		$value = $this->logged_cache->decrement($key, 1);
		$this->assertEquals($value, 2);

		$value = $this->logged_cache->decrement($key, 10);
		$this->assertEquals($value, -8);

		$this->logged_cache->set($key, "stop", 0);
		$value = $this->logged_cache->get($key);
		$this->assertEquals($value, "stop");

		$value = $this->logged_cache->increment($key, 11);
		$this->assertEquals($value, 11);

		$this->logged_cache->set($key, "stop", 0);
		$value = $this->logged_cache->get($key);
		$this->assertEquals($value, "stop");

		$value = $this->logged_cache->decrement($key, 9000);
		$this->assertEquals($value, 0);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testAddTwice($key)
	{
		$this->expectException(\LupusMichaelis\NestedCache\AlreadyCached::class);

		$this->logged_cache->add($key, 'salmon', 0);
		$this->logged_cache->add($key, 'trout', 0);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testReplaceNonExistant($key)
	{
		$this->expectException(\Exception::class);

		$this->logged_cache->replace($key, 'salmon', 0);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testReplaceExisting($key)
	{
		$this->logged_cache->add($key, 'trout', 0);
		$value = $this->logged_cache->get($key);
		$this->assertEquals($value, 'trout');

		$this->logged_cache->replace($key, 'salmon', 0);
		$value = $this->logged_cache->get($key);
		$this->assertEquals($value, 'salmon');
	}

	public function provideKeys()
	{
		return
			[ [new \LupusMichaelis\NestedCache\Key\Cut(['blog_id' => 1, 'group' => 'group', 'name' => 'target'])]
			];
	}
}
