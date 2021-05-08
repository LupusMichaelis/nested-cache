<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

class ObjectCacheKeeper
	implements KeeperInterface
	, ObjectCacheInterface
{
	public function __construct()
	{
		$this->stats = new Stats;
	}

	public function get_stats(): Stats
	{
		return $this->stats;
	}

	public function add_group(string $name, bool $is_persistent): void
	{
		if($is_persistent)
			$this->persistent_group_list[(string) $name] = true;
		else
			$this->non_persistent_group_list[(string) $name] = true;
	}

	public function get(Key\Cut $key)
	{
		$cache = $this->get_cache_for_key($key);

		if(!isset($cache[$key->get_name()]))
			throw new NotFound($key);

		return $cache[$key->get_name()];
	}

	public function set(Key\Cut $key, $value): void
	{
		$cache = $this->get_cache_for_key($key);
		$cache[$key->get_name()] = is_scalar($value) ? $value : clone $value;
	}

	public function add(Key\Cut $key, $value):void
	{
		$cache = $this->get_cache_for_key($key);

		if(isset($cache[$key->get_name()]))
			throw new AlreadyCached($key);

		$cache[$key->get_name()] = is_scalar($value) ? $value : clone $value;
	}

	public function replace(Key\Cut $key, $value):void
	{
		$cache = $this->get_cache_for_key($key);

		if(!isset($cache[$key->get_name()]))
			throw new NotFound($key);

		$cache[$key->get_name()] = is_scalar($value) ? $value : clone $value;
	}

	public function increment(Key\Cut $key, int $bump): int
	{
		try
		{
			$value = $this->get($key);
		}
		catch(NotFound $e)
		{
			$value = WordPress\ObjectCacheInterface::default_incrementable_floor;
		}
		$value += $bump;
		$this->set($key, $value);

		return $value;
	}

	public function decrement(Key\Cut $key, int $bump): int
	{
		try
		{
			$value = $this->get($key);
		}
		catch(NotFound $e)
		{
			$value = WordPress\ObjectCacheInterface::default_incrementable_floor;
		}
		$value -= $bump;
		$value = max($value, WordPress\ObjectCacheInterface::default_incrementable_floor);
		$this->set($key, $value);

		return $value;
	}

	public function delete(Key\Cut $key): void
	{
		$cache = $this->get_cache_for_key($key);
		unset($cache[$key->get_name()]);
	}

	public function flush(): void
	{
		$this->cache_list = [];
	}

	private function get_cache_for_key(Key\Cut $key)
	{
		if(!isset($this->cache_list[$key->get_blog_id()]))
			$this->cache_list[$key->get_blog_id()] = [];

		if(!isset($this->cache_list[$key->get_blog_id()][$key->get_group()]))
			// Use \ArrayObject to avoid reference problems with bare array
			$this->cache_list[$key->get_blog_id()][$key->get_group()] = new \ArrayObject;

		return $this->cache_list[$key->get_blog_id()][$key->get_group()];
	}

	private $default_group_name = WordPress\ObjectCacheInterface::default_group_name;

	private $caches = []; ///< @property array $caches

	private $non_persistent_group_list = []; ///< @property bool[string]
	private $persistent_group_list = []; ///< @property bool[string]
	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
}
