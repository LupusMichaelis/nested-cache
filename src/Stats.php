<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

class Stats
   implements	StatsInterface
{
	public function increment_misses(): void
	{
		++$this->misses;
	}

	public function increment_hits(): void
	{
		++$this->hits;
	}

	public function get_misses(): int
	{
		return $this->misses;
	}

	public function get_hits(): int
	{
		return $this->hits;
	}

	public function reset(): void
	{
		$this->misses = 0;
		$this->hits = 0;
	}

	private $misses = 0; ///< @property int $misses
	private $hits = 0; ///< @property int $hits
}
