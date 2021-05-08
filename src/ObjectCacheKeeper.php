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
		return $cache->get($key);
	}

	public function set(Key\Cut $key, $value): void
	{
		$cache = $this->get_cache_for_key($key);
		$cache->set($key, $value);
	}

	public function add(Key\Cut $key, $value): void
	{
		$cache = $this->get_cache_for_key($key);
		$cache->add($key, $value);
	}

	public function replace(Key\Cut $key, $value): void
	{
		$cache = $this->get_cache_for_key($key);
		$cache->replace($key, $value);
	}

	public function increment(Key\Cut $key, int $bump): int
	{
		$cache = $this->get_cache_for_key($key);
		return $cache->increment($key, $bump);
	}

	public function decrement(Key\Cut $key, int $bump): int
	{
		$cache = $this->get_cache_for_key($key);
		return $cache->decrement($key, $bump);
	}

	public function delete(Key\Cut $key): void
	{
		$cache = $this->get_cache_for_key($key);
		$cache->delete($key);
	}

	public function flush(): void
	{
		array_map
			( function ($g)
				{
					array_map(function($c) {$c->flush();}, $g);
				}
			, $this->cache_list
			);
	}

	private function get_cache_for_key(Key\Cut $key)
	{
		if(!isset($this->cache_list[$key->get_blog_id()]))
			$this->cache_list[$key->get_blog_id()] = [];

		if(!isset($this->cache_list[$key->get_blog_id()][$key->get_group()]))
			$this->cache_list[$key->get_blog_id()][$key->get_group()] = new ObjectCache\BareArray;

		return $this->cache_list[$key->get_blog_id()][$key->get_group()];
	}

	private $default_group_name = WordPress\ObjectCacheInterface::default_group_name;

	private $cache_list = []; ///< @property array $cache_list

	private $non_persistent_group_list = []; ///< @property bool[string]
	private $persistent_group_list = []; ///< @property bool[string]
	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
}
