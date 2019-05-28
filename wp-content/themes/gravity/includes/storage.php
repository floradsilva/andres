<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('gravity_storage_get')) {
	function gravity_storage_get($var_name, $default='') {
		global $GRAVITY_STORAGE;
		return isset($GRAVITY_STORAGE[$var_name]) ? $GRAVITY_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('gravity_storage_set')) {
	function gravity_storage_set($var_name, $value) {
		global $GRAVITY_STORAGE;
		$GRAVITY_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('gravity_storage_empty')) {
	function gravity_storage_empty($var_name, $key='', $key2='') {
		global $GRAVITY_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($GRAVITY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($GRAVITY_STORAGE[$var_name][$key]);
		else
			return empty($GRAVITY_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('gravity_storage_isset')) {
	function gravity_storage_isset($var_name, $key='', $key2='') {
		global $GRAVITY_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($GRAVITY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($GRAVITY_STORAGE[$var_name][$key]);
		else
			return isset($GRAVITY_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('gravity_storage_inc')) {
	function gravity_storage_inc($var_name, $value=1) {
		global $GRAVITY_STORAGE;
		if (empty($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = 0;
		$GRAVITY_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('gravity_storage_concat')) {
	function gravity_storage_concat($var_name, $value) {
		global $GRAVITY_STORAGE;
		if (empty($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = '';
		$GRAVITY_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('gravity_storage_get_array')) {
	function gravity_storage_get_array($var_name, $key, $key2='', $default='') {
		global $GRAVITY_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($GRAVITY_STORAGE[$var_name][$key]) ? $GRAVITY_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($GRAVITY_STORAGE[$var_name][$key][$key2]) ? $GRAVITY_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('gravity_storage_set_array')) {
	function gravity_storage_set_array($var_name, $key, $value) {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if ($key==='')
			$GRAVITY_STORAGE[$var_name][] = $value;
		else
			$GRAVITY_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('gravity_storage_set_array2')) {
	function gravity_storage_set_array2($var_name, $key, $key2, $value) {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if (!isset($GRAVITY_STORAGE[$var_name][$key])) $GRAVITY_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$GRAVITY_STORAGE[$var_name][$key][] = $value;
		else
			$GRAVITY_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('gravity_storage_merge_array')) {
	function gravity_storage_merge_array($var_name, $key, $value) {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if ($key==='')
			$GRAVITY_STORAGE[$var_name] = array_merge($GRAVITY_STORAGE[$var_name], $value);
		else
			$GRAVITY_STORAGE[$var_name][$key] = array_merge($GRAVITY_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('gravity_storage_set_array_after')) {
	function gravity_storage_set_array_after($var_name, $after, $key, $value='') {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if (is_array($key))
			gravity_array_insert_after($GRAVITY_STORAGE[$var_name], $after, $key);
		else
			gravity_array_insert_after($GRAVITY_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('gravity_storage_set_array_before')) {
	function gravity_storage_set_array_before($var_name, $before, $key, $value='') {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if (is_array($key))
			gravity_array_insert_before($GRAVITY_STORAGE[$var_name], $before, $key);
		else
			gravity_array_insert_before($GRAVITY_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('gravity_storage_push_array')) {
	function gravity_storage_push_array($var_name, $key, $value) {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($GRAVITY_STORAGE[$var_name], $value);
		else {
			if (!isset($GRAVITY_STORAGE[$var_name][$key])) $GRAVITY_STORAGE[$var_name][$key] = array();
			array_push($GRAVITY_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('gravity_storage_pop_array')) {
	function gravity_storage_pop_array($var_name, $key='', $defa='') {
		global $GRAVITY_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($GRAVITY_STORAGE[$var_name]) && is_array($GRAVITY_STORAGE[$var_name]) && count($GRAVITY_STORAGE[$var_name]) > 0) 
				$rez = array_pop($GRAVITY_STORAGE[$var_name]);
		} else {
			if (isset($GRAVITY_STORAGE[$var_name][$key]) && is_array($GRAVITY_STORAGE[$var_name][$key]) && count($GRAVITY_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($GRAVITY_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('gravity_storage_inc_array')) {
	function gravity_storage_inc_array($var_name, $key, $value=1) {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if (empty($GRAVITY_STORAGE[$var_name][$key])) $GRAVITY_STORAGE[$var_name][$key] = 0;
		$GRAVITY_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('gravity_storage_concat_array')) {
	function gravity_storage_concat_array($var_name, $key, $value) {
		global $GRAVITY_STORAGE;
		if (!isset($GRAVITY_STORAGE[$var_name])) $GRAVITY_STORAGE[$var_name] = array();
		if (empty($GRAVITY_STORAGE[$var_name][$key])) $GRAVITY_STORAGE[$var_name][$key] = '';
		$GRAVITY_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('gravity_storage_call_obj_method')) {
	function gravity_storage_call_obj_method($var_name, $method, $param=null) {
		global $GRAVITY_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($GRAVITY_STORAGE[$var_name]) ? $GRAVITY_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($GRAVITY_STORAGE[$var_name]) ? $GRAVITY_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('gravity_storage_get_obj_property')) {
	function gravity_storage_get_obj_property($var_name, $prop, $default='') {
		global $GRAVITY_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($GRAVITY_STORAGE[$var_name]->$prop) ? $GRAVITY_STORAGE[$var_name]->$prop : $default;
	}
}
?>