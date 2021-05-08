<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\WordPress;

use PHPUnit\Framework\TestCase;

class ObjectCacheTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->cache = new \LupusMichaelis\NestedCache\WordPress\ObjectCache;
	}

	public function tearDown(): void
	{
		unset($this->cache);
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\WordPress\ObjectCacheInterface::class, $this->cache);
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

	public function testSwitchBlogAndBack()
	{
		$key = 48;

		$result = $this->cache->incr($key, 10);
		$this->assertEquals(10, $result);
		$result = $this->cache->get($key);
		$this->assertEquals(10, $result);

		$this->cache->switch_to_blog(84);
		$result = $this->cache->get($key);
		$this->assertNull($result);

		$this->cache->switch_to_blog(0);
		$result = $this->cache->get($key);
		$this->assertEquals(10, $result);
	}

	/**
	 * @testWith [12]
	 *			 ["42"]
	 *			 ["My not so long key"]
	 *
	 */
	public function testAddScalar($value)
	{
		$key = 48;

		$success = $this->cache->add($key, $value);
		$this->assertTrue($success);
		$result = $this->cache->get($key);
		$this->assertEquals($value, $result);
		$this->assertSame($value, $result);

		$this->cache->switch_to_blog(84);
		$result = $this->cache->get($key);
		$this->assertNull($result);

		$this->cache->switch_to_blog(0);
		$result = $this->cache->get($key);
		$this->assertSame($value, $result);

		$success = $this->cache->add($key, 'something else');
		$this->assertFalse($success);
	}

	public function provideAddObject(): array
	{
		return
			[ [ (object) ['proA' => 'valueA'] ]
			, [ new \ArrayObject(['first', 1, null, (object) []]) ]
			];
	}

	/**
	 * @dataProvider provideAddObject
	 *
	 */
	public function testAddObject($value)
	{
		$key = 48;

		$success = $this->cache->add($key, $value);
		$this->assertTrue($success);
		$result = $this->cache->get($key);
		$this->assertEquals($value, $result);
		$this->assertNotSame($value, $result);

		$this->cache->switch_to_blog(84);
		$result = $this->cache->get($key);
		$this->assertNull($result);

		$this->cache->switch_to_blog(0);
		$result = $this->cache->get($key);
		$this->assertNotSame($value, $result);

		$success = $this->cache->add($key, 'something else');
		$this->assertFalse($success);
	}

	/**
	 * @testWith ["mykey", 42]
	 */
	public function testReplaceNonExistent($key, $replacement)
	{
		$success = $this->cache->replace($key, $replacement);
		$this->assertFalse($success);
	}


	/**
	 * @testWith ["mykey", 42, 85]
	 */
	public function testSuccessfulReplace($key, $original, $replacement)
	{
		$success = $this->cache->add($key, $original);
		$this->assertTrue($success);
		$result = $this->cache->get($key);
		$this->assertEquals($original, $result);
		$succes = $this->cache->replace($key, $replacement);
		$this->assertTrue($success);
		$result = $this->cache->get($key);
		$this->assertEquals($replacement, $result);
	}

	public function testGetMultiple()
	{
		$key_list = range(1, 10);
		$result = $this->cache->get_multiple($key_list);
		$this->assertCount(count($key_list), $result);
	}

	/**
	 * @dataProvider provideGet
	 */
	public function testGet($key, $value)
	{
		$returned = $this->cache->get($key);
		$this->assertNull($returned);

		$this->cache->add($key, $value);
		$result = $this->cache->get($key);
		$this->assertEquals($value, $result);
		$this->assertSame($value, $result);
	}

	public function provideGet()
	{
		return
			[ ["mykey", "value"]
			, [ ['no' => 'good'], "some"]
			,
				[ $this->createStub(\jsonserializable::class)
				, 'my key\'s name\'s JSON'
				]
			,
				[ new \stdclass
				, 'This makes no sense'
				]
			];
	}

	public function testStats()
	{
		$stats = $this->cache->stats();
		$this->assertNotEmpty($stats);
	}

	public function testGroup()
	{
		$this->cache->add_global_groups('yolo');
		$this->cache->add_non_persistent_groups('yolo');

		/// @todo assert internal state of $this->cache after group addition
		$this->assertTrue(true);
	}

	private $cache;
}
