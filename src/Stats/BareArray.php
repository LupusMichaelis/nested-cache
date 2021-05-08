<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Stats;

use LupusMichaelis\NestedCache as LMNC;

class BareArray
	extends		LMNC\AbstractStats
	implements	LMNC\StatsInterface
{
	public function increment_misses(): void
	{
		++$this->misses;
	}

	public function increment_hits(): void
	{
		++$this->hits;
	}

	public function reset(): void
	{
		$this->misses = 0;
		$this->hits = 0;
	}
}
