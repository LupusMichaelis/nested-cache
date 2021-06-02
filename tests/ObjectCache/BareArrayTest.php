<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\ObjectCache;

use PHPUnit\Framework\TestCase;

class BareArrayTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->cache = new \LupusMichaelis\NestedCache\ObjectCache\BareArray;
	}

	public function tearDown(): void
	{
		unset($this->cache);
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\BareArray::class, $this->cache);
	}

	public function testAddNonClonableObject()
	{
		$not_scalar_non_clonable = new \Error('My not clonable error object');
		$key = new \LupusMichaelis\NestedCache\Key\Cut(['blog_id' => 1, 'group' => 'group', 'name' => 'target']);

		$this->cache->add($key, $not_scalar_non_clonable, 0);
		$stored_value = $this->cache->get($key);
		$this->assertEquals($not_scalar_non_clonable, $stored_value);
	}
}
