<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Stats;

use LupusMichaelis\NestedCache as LMNC;

class Json
	implements \JsonSerializable
{
	public function __construct(LMNC\StatsInterface $stats)
	{
		$this->stats = $stats;
	}

	public function __tostring(): string
	{
		return json_encode($this);
	}

	public function jsonSerialize()
	{
		return
			[ 'hits' => $this->stats->get_hits()
			, 'misses' => $this->stats->get_misses()
			];
	}

	private $stats; ///< @property LMNC\StatsInterface $stats
}
