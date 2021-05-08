<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

/**
 * @param $that object
 * @param $function_name string
 * @param $values &array
 * @throws \ReflectionException	If array of values is too short and there is no default value
 * @return array[mixed]
 */
function method_parameters_as_map(object $that, string $function_name, array & $values)
{
	$object_glimpse = new \ReflectionClass($that);
	$function_glimpse = $object_glimpse->getMethod($function_name);
	$parameter_list = $function_glimpse->getParameters();

	$count_values = count($values);
	$value_list = [];
	foreach($parameter_list as $parameter_position => $parameter_glimpse)
		$value_list[$parameter_glimpse->getName()] =
			$parameter_position < $count_values
				?  $values[$parameter_position]
				: $parameter_glimpse->getDefaultValue()
				;

	return $value_list;
}
