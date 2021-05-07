<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\WordPress\ObjectCache;
use LupusMichaelis\NestedCache\WordPress\ObjectCacheInterface;
use LupusMichaelis\NestedCache\Stats;
use LupusMichaelis\NestedCache;

/**
 * Use PHP's Array and ArrayObject to provide a non persistent cache
 */
class BareArray
	implements ObjectCacheInterface
{
	public function __construct()
	{
		$this->stats = new Stats;
	}

	public function __destruct()
	{
		$this->close();
	}

	public function get($key, string $group = self::default_group_name, bool $force = false, &$found = null)
	{
		return $this->get_value_or_default($group, $key, self::default_value, $found);
	}

	public function get_multiple(array $keys, $group = self::default_group_name, bool $force = false): array
	{
		return array_combine
			( $keys
			, array_map(function($key) use(&$group, &$force) { return $this->get($key, $group, $force); }, $keys)
			);
	}

	public function set($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
		$cache = $this->get_cache_for_group($group);
		$key = $this->coerce_key($key);
		$cache[$key] = is_scalar($data) ? $data : clone $data;

		return true;
	}

	public function add($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
		$cache = $this->get_cache_for_group($group);

		$key = $this->coerce_key($key);
		if(isset($cache[$key]))
			return false;

		return $this->set($key, $data, $group, $expires);
	}

	public function add_global_groups($groups): void
	{
		// Noop as the bare array will be freed at the end of the run
	}

	public function add_non_persistent_groups($groups): void
	{
		// Noop as the bare array will be freed at the end of the run
	}

	public function replace($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
		$cache = $this->get_cache_for_group($group);

		$key = $this->coerce_key($key);
		if(!isset($cache[$key]))
			return false;

		return $this->set($key, $data, $group, $expires);
	}

	public function incr($key, int $bump = 1, string $group = self::default_group_name)
	{
		$value = $this->get_value_or_default($group, $key, self::default_incrementable_floor);
		$value += $bump;

		$cache = $this->get_cache_for_group($group);
		$key = $this->coerce_key($key);
		return $cache[$key] = $value;
	}

	public function decr($key, int $bump = 1, string $group = self::default_group_name)
	{
		$value = $this->get_value_or_default($group, $key, self::default_incrementable_floor);
		$value -= $bump;

		$cache = $this->get_cache_for_group($group);
		$key = $this->coerce_key($key);
		return $cache[$key] = max(self::default_incrementable_floor, $value);
	}

	public function delete($key, string $group = self::default_group_name): bool
	{
		$cache = $this->get_cache_for_group($group);
		$key = $this->coerce_key($key);
		unset($cache[$key]);
		return true;
	}

	public function flush(): bool
	{
		$this->cache = [];
		return true;
	}

	public function switch_to_blog(int $blog_id): int
	{
		return $this->blog_id = $blog_id;
	}

	public function close(): bool
	{
		// Beware! We're flushing because of implementation details. Emptying the array
		// is equivalent to shutting the link to the array in this case.
		return $this->flush();
	}

	public function stats(): string
	{
		return (string) new Stats\Html($this->stats);
	}

	// We do what we can, but in the end, if we can't properly corece key's type, we fail
	private function coerce_key($any)
	{
		if(is_numeric($any) || is_string($any))
			return $any;

		if($any instanceof \jsonserializable)
			return json_encode($any);

		if(is_object($any) && method_exists($any, '__tostring'))
			return (string) $any;

		return \serialize($any);
	}

	private function get_cache_for_group(string $group)
	{
		if(empty($group))
			$group = self::default_group_name;

		if(!isset($this->cache[$this->blog_id]))
			$this->cache[$this->blog_id] = [];

		if(!isset($this->cache[$this->blog_id][$group]))
			// Use \ArrayObject to avoid reference problems with bare array
			$this->cache[$this->blog_id][$group] = new \ArrayObject;

		return $this->cache[$this->blog_id][$group];
	}

	private function get_value_or_default(string $group, $key, $default, bool &$found = null)
	{
		$key = $this->coerce_key($key);
		$cache = $this->get_cache_for_group($group);
		$found = isset($cache[$key]);
		return $found ? $cache[$key] : $default;
	}

	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
	private $global_group_list = []; ///< @property bool[string]
	private $blog_id = 0; ///< @property int $blog_id
	private $cache = []; ///< @property array $cache
}
