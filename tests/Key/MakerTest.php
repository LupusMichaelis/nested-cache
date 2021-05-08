<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\Key;

use PHPUnit\Framework\TestCase;

class MakerTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->key_maker = new \LupusMichaelis\NestedCache\Key\Maker('bandit', 42);;
	}

	public function tearDown(): void
	{
		unset($this->key_maker);
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\Key\Maker::class, $this->key_maker);

		$this->assertEquals(42, $this->key_maker->get_blog_id());
		$this->assertEquals('bandit', $this->key_maker->get_group());

		$key = $this->key_maker->make('my reference');
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\Key\Cut::class, $key);
	}

	public function testChangeRealm()
	{
		$this->key_maker->set_blog_id(68);
		$this->assertEquals(68, $this->key_maker->get_blog_id());
		$this->assertEquals('bandit', $this->key_maker->get_group());

		$this->key_maker->set_group('burglars');
		$this->assertEquals(68, $this->key_maker->get_blog_id());
		$this->assertEquals('burglars', $this->key_maker->get_group());
	}
}
