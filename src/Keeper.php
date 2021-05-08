<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

class Keeper
	implements KeeperInterface
{
	public function __construct()
	{
		$this->stats = new Stats;
	}

	public function get_stats(): Stats
	{
		return $this->stats;
	}

	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
}
