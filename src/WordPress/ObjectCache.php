<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\WordPress;

use LupusMichaelis\NestedCache as LMNC;

class ObjectCache
	implements ObjectCacheInterface
{
	public function __construct()
	{
		$this->keeper = new LMNC\ObjectCacheKeeper;
		$this->keeper->set_persistent_cache_class(LMNC\ObjectCache\Apcu::class);
		$this->key_maker = new LMNC\Key\Maker(self::default_group_name, self::default_blog_id);
	}

	public function __destruct()
	{
		$this->close();
	}

	public function get($key, string $group = self::default_group_name, bool $force = false, &$found = null)
	{
		$key = $this->make_key($key, $group);

		try
		{
			$value = $this->keeper->get($key);
		}
		catch(LMNC\NotFound $e)
		{
			$value = self::default_value;
			$found = false;
		}

		return $value;
	}

	public function get_multiple(array $keys, $group = self::default_group_name, bool $force = false): array
	{
		return array_combine
			( $keys
			, array_map(function($key) use(&$group, &$force) { return $this->get($key, $group, $force); }, $keys)
			);
	}

	public function set($key, $data, string $group = self::default_group_name, int $expires_in = self::default_expires_in): bool
	{
		$key = $this->make_key($key, $group);
		$this->keeper->set($key, $data, $expires_in);

		return true;
	}

	public function add($key, $data, string $group = self::default_group_name, int $expires_in = self::default_expires_in): bool
	{
		$key = $this->make_key($key, $group);

		try
		{
			$this->keeper->add($key, $data, $expires_in);
			return true;
		}
		catch(LMNC\AlreadyCached $e)
		{
			return false;
		}
	}

	public function add_global_groups($groups): void
	{
		$groups = (array) $groups;
		foreach($groups as $group)
			$this->keeper->add_group($group, true);
	}

	public function add_non_persistent_groups($groups): void
	{
		$groups = (array) $groups;
		foreach($groups as $group)
			$this->keeper->add_group($group, false);
	}

	public function replace($key, $data, string $group = self::default_group_name, int $expires_in = self::default_expires_in): bool
	{
		$key = $this->make_key($key, $group);

		try
		{
			$this->keeper->replace($key, $data, $expires_in);
			return true;
		}
		catch(LMNC\NotFound $e)
		{
			return false;
		}
	}

	public function incr($key, int $bump = 1, string $group = self::default_group_name)
	{
		$key = $this->make_key($key, $group);
		return $this->keeper->increment($key, $bump);
	}

	public function decr($key, int $bump = 1, string $group = self::default_group_name)
	{
		$key = $this->make_key($key, $group);
		return $this->keeper->decrement($key, $bump);
	}

	public function delete($key, string $group = self::default_group_name): bool
	{
		$key = $this->make_key($key, $group);
		$this->keeper->delete($key);
		return true;
	}

	public function flush(): bool
	{
		$this->keeper->flush();
		return true;
	}

	public function switch_to_blog(int $blog_id): int
	{
		$this->key_maker->set_blog_id($blog_id);
		return $blog_id;
	}

	public function close(): bool
	{
		// Beware! We're flushing because of implementation details. Emptying the array
		// is equivalent to shutting the link to the array in this case.
		return $this->flush();
	}

	public function stats(): string
	{
		return (string) new LMNC\Stats\Html($this->keeper->get_stats());
	}

	private function make_key($key, $group)
	{
		$key = $this->coerce_key($key);
		$group = empty($group) ? self::default_group_name : $group;
		$key = $this->key_maker->make($key, $group);

		return $key;
	}

	// We do what we can, but in the end, if we can't properly corece key's type, we fail
	private function coerce_key($any)
	{
		if(is_numeric($any) || is_string($any))
			return (string) $any;

		if($any instanceof \jsonserializable)
			return json_encode($any);

		if(is_object($any) && method_exists($any, '__tostring'))
			return (string) $any;

		return \serialize($any);
	}

	private $key_maker; ///< @property \LupusMichaelis\NestedCache\Key\Maker
	private $keeper; ///< @property \LupusMichaelis\NestedCache\KeeperInterface
}
