<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\WordPress\ObjectCache;

/**
 * A fake persistent cacher
 */
class PseudoPersistent
	extends AbstractPersistent
{
	public function __construct()
	{
		$this->delegate = new BareArray;
	}

	public function get($key, string $group = self::default_group_name, bool $force = false, &$found = null)
	{
		return $this->delegate->get($key, $group, $force, $found);
	}

	public function get_multiple(array $keys, $group = self::default_group_name, bool $force = false): array
	{
		return $this->delegate->get_multiple($keys, $group, $force = false);
	}

	public function set($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
		return $this->delegate->set($key, $data, $group, $expires);
	}

	public function add($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
		return $this->delegate->add($key, $data, $group, $expires);
	}

	public function replace($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
		return $this->delegate->replace($key, $data, $group, $expires);
	}

	public function incr($key, int $bump = 1, string $group = self::default_group_name)
	{
		return $this->delegate->incr($key, $bump = 1, $group);
	}

	public function decr($key, int $bump = 1, string $group = self::default_group_name)
	{
		return $this->delegate->decr($key, $bump = 1, $group);
	}

	public function delete($key, string $group = self::default_group_name): bool
	{
		return $this->delegate->delete($key, $group);
	}

	public function flush(): bool
	{
		return $this->delegate->flush();
	}

	public function switch_to_blog(int $blog_id): int
	{
		return $this->delegate->switch_to_blog($blog_id);
	}

	public function close(): bool
	{
		return $this->delegate->close();
	}

	public function stats(): string
	{
		return $this->delegate->stats();
	}

	private $delegate = null;

	private $non_persistent_group_list = []; ///< @property bool[string]
	private $global_group_list = []; ///< @property bool[string]
}
