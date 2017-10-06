<?php

/**
 * Groups an array by a given key. Any additional keys will be used for grouping
 * the next set of sub-arrays.
 *
 * @author Jake Zatecky
 *
 * @param array $arr The array to have grouping performed on.
 * @param mixed $key The key to group or split by.
 *
 * @return array
 */
function array_group_by($arr, $key)
{
    if (!is_array($arr)) {
        trigger_error('array_group_by(): The first argument should be an array', E_USER_ERROR);
    }
    if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
        trigger_error('array_group_by(): The key should be a string, an integer, a float, or a function', E_USER_ERROR);
    }

    $isFunction = !is_string($key) && is_callable($key);

    // Load the new array, splitting by the target key
    $grouped = [];
    foreach ($arr as $value) {
        $groupKey = $isFunction ? $key($value) : $value[$key];
        $grouped[$groupKey][] = $value;
    }

    // Recursively build a nested grouping if more parameters are supplied
    // Each grouped array value is grouped according to the next sequential key
    if (func_num_args() > 2) {
        $args = func_get_args();

        foreach ($grouped as $groupKey => $value) {
            $params = array_merge([$value], array_slice($args, 2, func_num_args()));
            $grouped[$groupKey] = call_user_func_array('array_group_by', $params);
        }
    }

    return $grouped;
}
