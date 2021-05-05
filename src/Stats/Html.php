<?php

namespace LupusMichaelis\NestedCache\Stats;

use LupusMichaelis\NestedCache\Stats;

class Html
{
	const default_template = '
		<p>
			<span>Hits:</span> %1$s<br />
			<span>Misses:</span> %2$s<br />
		<p>
	';

	public function __construct(Stats $stats)
	{
		$this->stats = $stats;
	}

	public function __tostring(): string
	{
		return sprintf(self::default_template, $this->stats->get_hits(), $this->stats->get_misses());
	}

	private $stats; ///< @property Stat $stats
}
