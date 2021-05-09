<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\ObjectCache;

use \LupusMichaelis\NestedCache as LMNC;

class Logger
	implements LMNC\ObjectCacheInterface
{
	public function __construct(LMNC\ObjectCacheInterface $monitored)
	{
		$this->stats = new LMNC\Stats\BareArray;
		$this->monitor = new LMNC\Logger;
	}

	public function get_stats(): LMNC\StatsInterface
	{
		return $this->monitored->get_stats();
	}

	public function get(LMNC\Key\Cut $key)
	{
		return $this->monitored->get($key);
	}

	public function set(LMNC\Key\Cut $key, $value): void
	{
		$this->monitored->set($key, $value);
	}

	public function add(LMNC\Key\Cut $key, $value): void
	{
		$this->monitored->add($key, $value);
	}

	public function replace(LMNC\Key\Cut $key, $value): void
	{
		$this->monitored->replace($key, $value);
	}

	public function delete(LMNC\Key\Cut $key): void
	{
		$this->monitored->delete($key);
	}

	public function increment(LMNC\Key\Cut $key, int $bump): int
	{
		return $this->monitored->increment($key, $bump);
	}

	public function decrement(LMNC\Key\Cut $key, int $bump): int
	{
		return $this->monitored->decrement($key, $bump);
	}

	public function flush(): void
	{
		$this->monitored->flush();
	}

	private $monitored; ///< @property LMNC\ObjectCacheInterface
}
