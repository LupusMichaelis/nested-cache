<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Tests;

use PHPUnit\Framework\TestCase;
use LupusMichaelis\NestedCache as LMNC;

class FreeFunctionsTest
	extends TestCase
{
	public function testMethodParametersAsMap()
	{
		$c = new class
			{
				function something($toto, $tata, $titi)
				{
					$args = func_get_args();
					return LMNC\method_parameters_as_map($this, __FUNCTION__, $args);
				}
			};

		$result = $c->something(1, 'plus', 'mieux');

		$this->assertEquals(['toto' => 1, 'tata' => 'plus', 'titi' => 'mieux'], $result);
	}
}
