<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\ObjectCache;

use PHPUnit\Framework\TestCase;

class FactoryTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->factory = new \LupusMichaelis\NestedCache\ObjectCache\Factory;
	}

	public function tearDown(): void
	{
		unset($this->factory);
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\Factory::class, $this->factory);
	}

	public function testDefaults()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\Apcu::class, $this->factory->get('persistent'));
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\BareArray::class, $this->factory->get('ephemeral'));
	}

	public function testLoggerEnabled()
	{
		$this->factory->set_options
			(
				[ 'persistent' => [ 'log' => true ]
				, 'ephemeral' => [ 'log' => true ]
				]
			);

		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\Logger::class, $this->factory->get('persistent'));
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\ObjectCache\Logger::class, $this->factory->get('ephemeral'));
	}

	public function testUnknownFactorable()
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Unknown target \'cat\'');
		$this->factory->get('cat');
	}

	public function testUnsupportedCache()
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Class \'Yolo\' not supported');
		$this->factory->set_options
			(
				[ 'cat' => [ 'log' => true, 'class' => 'Yolo' ]
				]
			);
		$this->factory->get('cat');
	}
}
