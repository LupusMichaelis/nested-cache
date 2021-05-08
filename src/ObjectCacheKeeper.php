<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

class ObjectCacheKeeper
	implements KeeperInterface
	, ObjectCacheInterface
{
	public function get_stats(): StatsInterface
	{
		$stat_list = $this->iterate_caches(function($c) {return $c->get_stats();});
		$stats = new Stats\BareArray;

		return array_reduce
			( $stat_list
			, function (StatsInterface $s, StatsInterface $e)
				{
					$s->set_hits($s->get_hits() + $e->get_hits());
					$s->set_misses($s->get_misses() + $e->get_misses());
					return $s;
				}
			, $stats
			);
	}

	public function add_group(string $name, bool $is_persistent): void
	{
		if($is_persistent)
		{
			$do_migrate = isset($this->non_persistent_group_list[(string) $name]);
			unset($this->non_persistent_group_list[(string) $name]);
			$this->persistent_group_list[(string) $name] = true;
		}
		else
		{
			$do_migrate = isset($this->persistent_group_list[(string) $name]);
			unset($this->persistent_group_list[(string) $name]);
			$this->non_persistent_group_list[(string) $name] = true;
		}

		$do_migrate and $this->migrate_group($name);
	}

	public function set_persistent_cache_class(string $class_name): void
	{
		$this->persistent_cache_class = $class_name;
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
		$this->iterate_caches(function($c) {$c->flush();});
	}

	private function iterate_caches($callback): array
	{
		return array_reduce
			( array_map
				( function (array $g) use(&$callback)
					{
						return array_map($callback, $g);
					}
				, $this->cache_list
				)
			, function (array $out, array $in)
				{
					return array_merge($out, $in);
				}
			, []
			);
	}

	private function get_cache_for_key(Key\Cut $key)
	{
		if(!isset($this->cache_list[$key->get_blog_id()]))
			$this->cache_list[$key->get_blog_id()] = [];

		if(!isset($this->cache_list[$key->get_blog_id()][$key->get_group()]))
			$this->instantiate_cache_for_group($key->get_group());

		return $this->cache_list[$key->get_blog_id()][$key->get_group()];
	}

	private function is_persistent(string $group)
	{
		return isset($this->persistent_group_list[$group]);
	}

	private function migrate_group(string $group)
	{
		foreach($this->cache_list as $blog_id => $grouped_cache_list)
			foreach($grouped_cache_list as $name => $cache)
			{
				$class = $this->is_persistent($key)
					? $this->persistent_cache_class
					: ObjectCache\BareArray::class
					;
				$this->cache_list[$blog_id][$group] = new $class;
			}
	}

	private function instantiate_cache_for_group(string $group_name)
	{
		$class = $this->is_persistent($group_name)
			? $this->persistent_cache_class
			: ObjectCache\BareArray::class
			;

		foreach($this->cache_list as $blog_id => $grouped_cache_list)
			if(!isset($this->cache_list[$blog_id][$group_name]))
				$this->cache_list[$blog_id][$group_name] = new $class;
	}

	private $default_group_name = WordPress\ObjectCacheInterface::default_group_name;

	private $cache_list = []; ///< @property array $cache_list

	private $persistent_cache_class = ObjectCache\BareArray::class; ///< @property string
	private $non_persistent_group_list = []; ///< @property bool[string]
	private $persistent_group_list = []; ///< @property bool[string]
}
