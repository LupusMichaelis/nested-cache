<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

class NotFound
	extends \Exception
{
	public function __construct(Key\Cut $key, $message = null, $code = 0, \Throwable $previous = null)
	{
		$this->key = $key;
		parent::__construct($this->buildMessage(), $code, $previous);
	}

	public function buildMessage()
	{
		return sprintf
			( 'Key (%d|%s|%s) not found'
			, $this->key->get_blog_id()
			, $this->key->get_group()
			, $this->key->get_name()
			);
	}

	public $key;
}
