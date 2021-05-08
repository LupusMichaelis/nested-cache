<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Stats;

use LupusMichaelis\NestedCache as LMNC;

class Html
{
	const default_template = '
		<p>
			<span>Hits:</span> %1$s<br />
			<span>Misses:</span> %2$s<br />
		<p>
	';

	public function __construct(LMNC\StatsInterface $stats)
	{
		$this->stats = $stats;
	}

	public function __tostring(): string
	{
		return sprintf(self::default_template, $this->stats->get_hits(), $this->stats->get_misses());
	}

	private $stats; ///< @property LMNC\StatsInterface $stats
}
