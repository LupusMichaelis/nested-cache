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

	public function testApcuGetNotFound()
	{
		$apcu_cache = new \LupusMichaelis\NestedCache\ObjectCache\Apcu;

		$key = new \LupusMichaelis\NestedCache\Key\Cut(['blog_id' => 1, 'group' => 'group', 'name' => 'target']);

		$stats = $apcu_cache->get_stats();
		$this->assertEquals(0, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$this->expectException(\LupusMichaelis\NestedCache\NotFound::class);
		$apcu_cache->get($key);
	}

	public function testApcuRun()
	{
		$apcu_cache = new \LupusMichaelis\NestedCache\ObjectCache\Apcu;

		$key = new \LupusMichaelis\NestedCache\Key\Cut(['blog_id' => 1, 'group' => 'group', 'name' => 'target']);

		$stats = $apcu_cache->get_stats();
		$this->assertEquals(0, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		try { $apcu_cache->get($key); } catch(\LupusMichaelis\NestedCache\NotFound $e) { }

		$stats = $apcu_cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$apcu_cache->set($key, 8956);
		$stats = $apcu_cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(0, $stats->get_hits());

		$apcu_cache->get($key);
		$stats = $apcu_cache->get_stats();
		$this->assertEquals(1, $stats->get_misses());
		$this->assertEquals(1, $stats->get_hits());
	}

	public function testAddNonClonableObject()
	{
		$not_scalar_non_clonable = new \Error('My not clonable error object');
		$key = new \LupusMichaelis\NestedCache\Key\Cut(['blog_id' => 1, 'group' => 'group', 'name' => 'target']);

		$this->cache->add($key, $not_scalar_non_clonable);
		$stored_value = $this->cache->get($key);
		$this->assertEquals($not_scalar_non_clonable, $stored_value);
	}
}
