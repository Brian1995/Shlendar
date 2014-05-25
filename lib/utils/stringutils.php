<?php

class StringUtils {

//	public static function asAttributeString(array $array) {
//		return implode(' ', array_map(function($k, $v){ return sprintf("%s=\"%s\"", $k, $v); }, array_keys($array), $array));
//	}
	
	public static function escapeHTML($text) {
		return htmlentities($text, ENT_QUOTES | ENT_IGNORE, 'UTF-8');
	}
	
	public static function startsWith($string, $prefix) {
		return $prefix === "" || substr($string , 0, strlen($prefix)) === $prefix;
	}
	
	private static function endsWith($string, $suffix) {
		return $suffix === "" || substr($string, -strlen($suffix)) === $suffix;
	}
	
}
