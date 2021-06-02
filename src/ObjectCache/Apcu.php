<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\ObjectCache;

use \LupusMichaelis\NestedCache as LMNC;

class Apcu
	implements LMNC\ObjectCacheInterface
{
	public function __construct()
	{
		if(!apcu_enabled())
			throw new \Exception('ACPu not enabled');

		$this->stats = new LMNC\Stats\Apcu;
	}

	public function get_stats(): LMNC\StatsInterface
	{
		$this->stats->refresh();
		return $this->stats;
	}

	public function get(LMNC\Key\Cut $key)
	{
		$success = false;
		$value = \apcu_fetch("$key", $success);

		if(false === $success)
			throw new LMNC\NotFound($key);

		return $value;
	}

	public function set(LMNC\Key\Cut $key, $value, int $expires_in): void
	{
		$success = \apcu_store("$key", $value, $expires_in);

		if(false === $success)
			// \apcu_store seems to fail only with non-string non-array keys, as I ensure "$key"
			// is a string, it can't fail, except in system failure (OOM, etc)
			throw new \Exception(sprintf('Error occured on setting \'%s\'', $key)); // @codeCoverageIgnore
	}

	public function add(LMNC\Key\Cut $key, $value, int $expires_in): void
	{
		$success = \apcu_add("$key", $value, $expires_in);

		if(false === $success)
			throw new LMNC\AlreadyCached($key);
	}

	public function replace(LMNC\Key\Cut $key, $value, int $expires_in): void
	{
		if(!\apcu_exists("$key"))
			throw new LMNC\NotFound($key);

		/// @fixme Race condition: if the value is deleted in between, the replace contract will be broken

		$this->set($key, $value, $expires_in);
	}

	public function increment(LMNC\Key\Cut $key, int $bump): int
	{
		$success = false;
		$value = \apcu_inc("$key", $bump, $success);

		if(false === $success)
		{
			$value = $bump + LMNC\WordPress\ObjectCacheInterface::default_incrementable_floor;
			$this->set($key, $value, LMNC\WordPress\ObjectCacheInterface::default_expires_in);
		}

		return $value;
	}

	public function decrement(LMNC\Key\Cut $key, int $bump): int
	{
		$success = false;
		$value = \apcu_dec("$key", $bump, $success);

		if(false === $success)
		{
			$value = LMNC\WordPress\ObjectCacheInterface::default_incrementable_floor;
			$this->set($key, $value, LMNC\WordPress\ObjectCacheInterface::default_expires_in);
		}

		return $value;
	}

	public function delete(LMNC\Key\Cut $key): void
	{
		\apcu_delete("$key");
	}

	public function flush(): void
	{
		\apcu_clear_cache();
	}

	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
}
