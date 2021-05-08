<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\Key;

use PHPUnit\Framework\TestCase;

class CutTest
	extends TestCase
{
	/**
	 * @testWith [ {"blog_id": 45, "group": "wanderer", "name": "me"} ]
	 */
	public function testSuccessInstantiate($path)
	{
		$this->cut = new \LupusMichaelis\NestedCache\Key\Cut($path);
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\Key\Cut::class, $this->cut);

		$this->assertEquals($path['blog_id'], $this->cut->get_blog_id());
		$this->assertEquals($path['group'], $this->cut->get_group());
		$this->assertEquals($path['name'], $this->cut->get_name());
	}

	/**
	 * @testWith [ {"blog_id": 45, "goup": "wanderer", "name": "me"} ]
	 */
	public function testFailingInstanciation($path)
	{
		$this->expectException(\Error::class);

		$this->cut = new \LupusMichaelis\NestedCache\Key\Cut($path);
	}
}
