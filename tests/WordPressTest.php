<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests;

use PHPUnit\Framework\TestCase;

class WordPressTest
	extends TestCase
{
	public function setUp(): void
	{
		require_once 'src/wordpress.php';
	}

	public function tearDown(): void
	{
		global $wp_object_cache;
		$wp_object_cache = null;
	}

	public function testCacheNoGroup()
	{
		\wp_cache_init();

		global $wp_object_cache;
		$this->assertNotNull($wp_object_cache);

		$value = \wp_cache_get('yoyo');
		$this->assertNull($value);

		$success = \wp_cache_add('yoyo', 'value');
		$value = \wp_cache_get('yoyo');
		$this->assertTrue($success);
		$this->assertEquals('value', $value);

		$success = \wp_cache_add('yoyo', 'value');
		$value = \wp_cache_get('yoyo');
		$this->assertFalse($success);
		$this->assertEquals('value', $value);

		$success = \wp_cache_set('plop', 'other value');
		$value = \wp_cache_get('plop');
		$this->assertTrue($success);
		$this->assertEquals('other value', $value);

		$success = \wp_cache_set('plop', 'different value');
		$value = \wp_cache_get('plop');
		$this->assertTrue($success);
		$this->assertEquals('different value', $value);

		$value_list = \wp_cache_get_multiple(['yoyo', 'plop']);
		$this->assertCount(2, $value_list);
		$this->assertContains('value', $value_list);
		$this->assertContains('different value', $value_list);
		$this->assertEquals
			(
				[ 'yoyo' => 'value'
				, 'plop' => 'different value'
				]
			, $value_list
			);

		\wp_cache_delete('yoyo');
		$value = \wp_cache_get('yoyo');
		$this->assertNull($value);

		$success = \wp_cache_replace('yoyo', 'replacement');
		$this->assertFalse($success);

		$success = \wp_cache_replace('plop', 'replacement');
		$value = \wp_cache_get('plop');
		$this->assertTrue($success);
		$this->assertEquals('replacement', $value);

		$success = \wp_cache_flush();
		$this->assertTrue($success);

		$value = \wp_cache_get('plop');
		$this->assertNull($value);

		$success = \wp_cache_flush();
		$this->assertTrue($success);

		$count = \wp_cache_incr('mr wolff');
		$this->assertEquals(1, $count);
		$count = \wp_cache_incr('mr wolff', 10);
		$this->assertEquals(11, $count);
		$count = \wp_cache_decr('mr wolff', 3);
		$this->assertEquals(8, $count);
		$count = \wp_cache_incr('mr wolff', 9);
		$this->assertEquals(17, $count);
		$count = \wp_cache_decr('mr wolff', 9000);
		$this->assertEquals(0, $count);

		$success = \wp_cache_close();
		$this->assertTrue($success);

		$this->assertNull($wp_object_cache);
	}

	public function testCacheWithBlogSwitching()
	{
		\wp_cache_init();

		global $wp_object_cache;
		$this->assertNotNull($wp_object_cache);

		$value = \wp_cache_get('yoyo');
		$this->assertNull($value);

		$success = \wp_cache_add('yoyo', 'value');
		$this->assertTrue($success);

		$value = \wp_cache_get('yoyo');
		$this->assertEquals('value', $value);

		$blog_id = \wp_cache_switch_to_blog(42);
		$this->assertEquals(42, $blog_id);

		$value = \wp_cache_get('yoyo');
		$this->assertNull($value);

		$success = \wp_cache_add('yoyo', 'other value');
		$this->assertTrue($success);
		$this->assertNull($value);

		$value = \wp_cache_get('yoyo');
		$this->assertEquals('other value', $value);

		$blog_id = \wp_cache_switch_to_blog(0);
		$this->assertEquals(0, $blog_id);

		$value = \wp_cache_get('yoyo');
		$this->assertEquals('value', $value);
	}

	public function testCacheGroups()
	{
		\wp_cache_init();

		global $wp_object_cache;
		$this->assertNotNull($wp_object_cache);

		\wp_cache_add_global_groups('toto');
		\wp_cache_add_non_persistent_groups('yoyo');

		$this->assertTrue(True);
	}
}
