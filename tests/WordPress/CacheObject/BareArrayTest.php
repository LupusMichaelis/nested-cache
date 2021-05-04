<?php

namespace LupusMichaelis\NestedCache\Tests\WordPress\CacheObject;

use PHPUnit\Framework\TestCase;

class BareArrayTest
	extends TestCase
{
	public function testInstantiate()
	{
		$o = new \LupusMichaelis\NestedCache\WordPress\CacheObject\BareArray;
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\WordPress\CacheObjectInterface::class, $o);
	}
}
