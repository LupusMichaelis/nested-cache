<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Stats;

use LupusMichaelis\NestedCache as LMNC;

class Apcu
	extends		LMNC\AbstractStats
	implements	LMNC\StatsInterface
{
	public function refresh(): void
	{
		$stats = \apcu_cache_info(true);
		$this->hits = (int) $stats['num_hits'];
		$this->misses = (int) $stats['num_misses'];
	}
}
