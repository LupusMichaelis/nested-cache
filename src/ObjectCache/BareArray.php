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
		if(!isset($this->cache["$key"]))
			throw new LMNC\NotFound($key);

		$cached = $this->cache["$key"];
		$value =
			is_string($cached)
				? @unserialize($cached)
				: $cached;
		
		return false === $value ? $cached : $value;
	}

	public function set(LMNC\Key\Cut $key, $value): void
	{
		if(is_object($value))
		{
			$glimpse = new \ReflectionObject($value);
			if($glimpse->isCloneable())
				$value = clone $value;
			else
				$value = serialize($value);
		}

		$this->cache["$key"] = $value;
	}

	public function add(LMNC\Key\Cut $key, $value):void
	{
		if(isset($this->cache["$key"]))
			throw new LMNC\AlreadyCached($key);

		$this->set($key, $value);
	}

	public function replace(LMNC\Key\Cut $key, $value):void
	{
		if(!isset($this->cache["$key"]))
			throw new LMNC\NotFound($key);

		$this->set($key, $value);
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
		unset($this->cache["$key"]);
	}

	public function flush(): void
	{
		$this->cache = [];
	}

	private $cache = []; ///< @property array $this->caches
	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
}
