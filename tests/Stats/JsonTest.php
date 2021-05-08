<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests\Stats;

use PHPUnit\Framework\TestCase;

class JsonTest
	extends TestCase
{
	public function testIncrementation()
	{
		$stat_stub = $this->createStub(\LupusMichaelis\NestedCache\AbstractStats::class);
		$stat_stub->method('get_misses')->willReturn(42);
		$stat_stub->method('get_hits')->willReturn(12);

		$stat_view = new \LupusMichaelis\NestedCache\Stats\Json($stat_stub);
		$this->assertInstanceOf(\LupusMichaelis\NestedCache\Stats\Json::class, $stat_view);

		$payload = json_encode($stat_view);

		$this->assertJsonStringEqualsJsonString
			( json_encode(["misses" => 42, "hits" => 12])
			, (string) $stat_view
			);

		$this->assertJsonStringEqualsJsonString
			( json_encode(["misses" => 42, "hits" => 12])
			, $payload
			);
	}
}
