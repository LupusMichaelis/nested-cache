<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\Serializer;

use PHPUnit\Framework\TestCase;

class NoopTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->serializer = new \LupusMichaelis\NestedCache\Serializer\Noop;
	}

	public function tearDown(): void
	{
		unset($this->serializer);
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\Serializer\Noop::class, $this->serializer);
	}

	public function provideSubjects()
	{
		return
			[ [42]
			, ["yoyo"]
			, [null]
			, [false]
			, [true]
			, [ [ [42], ["yoyo"], [null], [false], [true] ] ]
			, [ (object) [ [42], ["yoyo"], [null], [false], [true] ] ]
			, [ new \Error('This is a mistake') ]
			, [ new \Exception('This is a mistake') ]
			];
	}

	/**
	 * @dataProvider provideSubjects
	 */
	public function testCast($value)
	{
		$this->assertEquals(42, $this->serializer->cast(42));
	}

	/**
	 * @dataProvider provideSubjects
	 */
	public function uncast($value)
	{
		$this->assertEquals($value, $this->serializer->uncast($value));
	}
}
