<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

function method_parameters_as_map(object $that, string $function_name, array & $values)
{
	$object_glimpse = new \ReflectionClass($that);
	$function_glimpse = $object_glimpse->getMethod($function_name);
	$parameter_list = $function_glimpse->getParameters();
	$parameter_list = \array_map(function(\ReflectionParameter $parameter_glimpse)
	{
		return $parameter_glimpse->getName();
	}, $parameter_list);

	return \array_combine($parameter_list, $values);
}
