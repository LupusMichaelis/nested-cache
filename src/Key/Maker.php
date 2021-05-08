<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Key;

use LupusMichaelis\NestedCache as LMNC;

class Maker
{
	public function __construct(string $group, int $blog_id)
	{
		$this->blog_id = $blog_id;
		$this->group = $group;
	}

	public function make(string $name, ?string $group = null, ?int $blog_id = null): Cut
	{
		$args = func_get_args();
		$args = LMNC\method_parameters_as_map($this, __FUNCTION__, $args);
		empty($args['group']) and $args['group'] = $this->group;
		empty($args['blog_id']) and $args['blog_id'] = $this->blog_id;

		$path = new Cut($args);
		return $path;
	}

	public function set_blog_id(int $blog_id): void
	{
		$this->blog_id = $blog_id;
	}

	public function set_group(string $group): void
	{
		$this->group = $group;
	}

	public function get_blog_id(): int
	{
		return $this->blog_id;
	}

	public function get_group(): string
	{
		return $this->group;
	}

	private $blog_id; ///< @property int
	private $group; ///< @property string
}
