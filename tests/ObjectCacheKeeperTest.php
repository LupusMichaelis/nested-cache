<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests;

use PHPUnit\Framework\TestCase;

class ObjectCacheKeeperTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->cache_keeper = new \LupusMichaelis\NestedCache\ObjectCacheKeeper;
	}

	public function tearDown(): void
	{
		unset($this->cache_keeper);
		\apcu_clear_cache();
	}

	public function provideKeys()
	{
		return
			[ [new \LupusMichaelis\NestedCache\Key\Cut(['blog_id' => 1, 'group' => 'bundle', 'name' => 'target'])]
			];
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCacheKeeper::class, $this->cache_keeper);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testChangeGroupWithoutChangingPersistent($key)
	{
		$this->cache_keeper->add_group('bundle', true);

		$this->cache_keeper->set($key, 42);
		$value = $this->cache_keeper->get($key);
		$this->assertEquals(42, $value);

		$this->cache_keeper->add_group('bundle', true);
		$value = $this->cache_keeper->get($key);
		$this->assertEquals(42, $value);

		$this->cache_keeper->add_group('bundle', false);
		$this->expectException(\LupusMichaelis\NestedCache\NotFound::class);
		$this->expectExceptionMessage('Key (1|bundle|target) not found');
		$value = $this->cache_keeper->get($key);
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testChangeGroupWithoutChangingNonPersistent($key)
	{
		$this->cache_keeper->add_group('bundle', false);

		$this->cache_keeper->set($key, 42);
		$value = $this->cache_keeper->get($key);
		$this->assertEquals(42, $value);

		$this->cache_keeper->add_group('bundle', false);
		$value = $this->cache_keeper->get($key);
		$this->assertEquals(42, $value);

		$this->cache_keeper->add_group('bundle', true);
		$this->expectException(\LupusMichaelis\NestedCache\NotFound::class);
		$this->expectExceptionMessage('Key (1|bundle|target) not found');
		$value = $this->cache_keeper->get($key);
	}
}
