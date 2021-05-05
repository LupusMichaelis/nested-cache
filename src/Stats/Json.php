<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Stats;

use LupusMichaelis\NestedCache\Stats;

class Json
	implements \JsonSerializable
{
	public function __construct(Stats $stats)
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

	private $stats; ///< @property Stat $stats
}
