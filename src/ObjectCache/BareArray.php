<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\ObjectCache;

use \LupusMichaelis\NestedCache as LMNC;

class BareArray
	implements LMNC\ObjectCacheInterface
{
	public function __construct()
	{
		$this->stats = new LMNC\Stats\BareArray;
	}

	public function get_stats(): LMNC\StatsInterface
	{
		return $this->stats;
	}

	public function get(LMNC\Key\Cut $key)
	{
		if(!isset($this->cache[$key->get_name()]))
			throw new LMNC\NotFound($key);

		return $this->cache[$key->get_name()];
	}

	public function set(LMNC\Key\Cut $key, $value): void
	{
		$this->cache[$key->get_name()] = is_scalar($value) ? $value : clone $value;
	}

	public function add(LMNC\Key\Cut $key, $value):void
	{
		if(isset($this->cache[$key->get_name()]))
			throw new LMNC\AlreadyCached($key);

		$this->cache[$key->get_name()] = is_scalar($value) ? $value : clone $value;
	}

	public function replace(LMNC\Key\Cut $key, $value):void
	{
		if(!isset($this->cache[$key->get_name()]))
			throw new LMNC\NotFound($key);

		$this->cache[$key->get_name()] = is_scalar($value) ? $value : clone $value;
	}

	public function increment(LMNC\Key\Cut $key, int $bump): int
	{
		try
		{
			$value = $this->get($key);
		}
		catch(LMNC\NotFound $e)
		{
			$value = LMNC\WordPress\ObjectCacheInterface::default_incrementable_floor;
		}
		$value += $bump;
		$this->set($key, $value);

		return $value;
	}

	public function decrement(LMNC\Key\Cut $key, int $bump): int
	{
		try
		{
			$value = $this->get($key);
		}
		catch(LMNC\NotFound $e)
		{
			$value = LMNC\WordPress\ObjectCacheInterface::default_incrementable_floor;
		}
		$value -= $bump;
		$value = max($value, LMNC\WordPress\ObjectCacheInterface::default_incrementable_floor);
		$this->set($key, $value);

		return $value;
	}

	public function delete(LMNC\Key\Cut $key): void
	{
		unset($this->cache[$key->get_name()]);
	}

	public function flush(): void
	{
		$this->cache = [];
	}

	private $cache = []; ///< @property array $this->caches
	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
}
