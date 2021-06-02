<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

/**
 * @param $that object
 * @param $function_name string
 * @param $values &array
 * @throws \ReflectionException	If array of values is too short and there is no default value
 * @return array[mixed]
 */
function method_parameters_as_map(object $that, string $function_name, array & $values): array
{
	$object_glimpse = new \ReflectionClass($that);
	$function_glimpse = $object_glimpse->getMethod($function_name);
	return function_glimpse_parameters_as_map($function_glimpse, $values);
}

function function_parameters_as_map(string $function_name, array & $values): array
{
	$function_glimpse = new \ReflectionFunction($function_name);
	return function_glimpse_parameters_as_map($function_glimpse, $values);
}

function function_glimpse_parameters_as_map(\ReflectionFunctionAbstract $function_glimpse, array & $values): array
{
	$parameter_list = $function_glimpse->getParameters();
	$count_values = count($values);
	$value_list = [];
	foreach($parameter_list as $parameter_position => $parameter_glimpse)
		if($parameter_position < $count_values)
			$value_list[$parameter_glimpse->getName()] = $values[$parameter_position];
		else
			if($parameter_glimpse->isPassedByReference())
				$value_list[$parameter_glimpse->getName()] = &by_ref($parameter_glimpse->getDefaultValue());
			else
				$value_list[$parameter_glimpse->getName()] = $parameter_glimpse->getDefaultValue();

	return $value_list;
}

function &by_ref($value)
{
	return $value;
}
