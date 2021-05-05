<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests;

use PHPUnit\Framework\TestCase;

class StatsTest
	extends TestCase
{
	public function setUp(): void
	{
		$this->stats = new \LupusMichaelis\NestedCache\Stats;
	}

	public function tearDown(): void
	{
		unset($this->stats);
	}

	public function testInstantiate()
	{
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\Stats::class, $this->stats);
	}

	public function testIncrementation()
	{
		$this->assertEquals(0, $this->stats->get_misses());
		$this->assertEquals(0, $this->stats->get_hits());

		$this->stats->increment_misses();
		$this->assertEquals(1, $this->stats->get_misses());
		$this->assertEquals(0, $this->stats->get_hits());

		$this->stats->increment_misses();
		$this->assertEquals(2, $this->stats->get_misses());
		$this->assertEquals(0, $this->stats->get_hits());

		$this->stats->increment_hits();
		$this->assertEquals(2, $this->stats->get_misses());
		$this->assertEquals(1, $this->stats->get_hits());

		$this->stats->reset();
		$this->assertEquals(0, $this->stats->get_misses());
		$this->assertEquals(0, $this->stats->get_hits());

		$this->stats->increment_misses();
		$this->stats->increment_hits();
		$this->assertEquals(1, $this->stats->get_misses());
		$this->assertEquals(1, $this->stats->get_hits());
	}
}
