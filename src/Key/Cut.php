<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Key;

class Cut
{
	private const parts = ['blog_id', 'group', 'name'];

	public function __construct(array $nodes)
	{
		$not_found_keys = array_diff(self::parts, array_keys($nodes));
		if(count($not_found_keys))
			throw new \Exception('Required keys not found \'[%s]\'', implode(', ', $not_found_keys));

		foreach(self::parts as $k)
			$this->{"set_$k"}($nodes[$k]);
	}

	public function set_blog_id(int $blog_id): void
	{
		$this->blog_id = $blog_id;
	}

	public function get_blog_id(): int
	{
		return $this->blog_id;
	}

	public function set_group(string $group): void
	{
		$this->group = $group;
	}

	public function get_group(): string
	{
		return $this->group;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	private $blog_id; ///< @property int
	private $group; ///< @property string
	private $name; ///< @property string
}
