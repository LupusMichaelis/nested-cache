<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

abstract class AbstractStats
	implements StatsInterface
{
	public function set_misses(int $misses): void
	{
		$this->misses = $misses;
	}

	public function get_misses(): int
	{
		return $this->misses;
	}

	public function get_hits(): int
	{
		return $this->hits;
	}

	public function set_hits(int $hits): void
	{
		$this->hits = $hits;
	}

	protected $misses = 0; ///< @property int $misses
	protected $hits = 0; ///< @property int $hits
}
