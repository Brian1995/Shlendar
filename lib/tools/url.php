<?php

include_once 'lib/external/http_build_url.php';

/**
 * Returns the full url of the current page, including protcol, host and 
 * request.
 * @return string
 */
function url_full() {
	$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
	return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function url_strip_query_parameters($url) {
	$parts = parse_url($url);
	$parts['query'] = NULL;
	return http_build_url(array(), $parts);
}

/**
 * Appends the parameter identified by name and value to the url inside the 
 * query part.
 * 
 * @param string $url
 * @param string $name
 * @param string $value
 * @return string
 */
function url_set_query_parameter($url, $name, $value) {
	$parts = parse_url($url);
	if (is_null($name)) {
		$parts['query'] = NULL;
	} else {
		if (!isset($parts['query'])) {
			$parts['query'] = '';
		}
		$query = array();
		parse_str($parts['query'], $query);
		if (is_null($value)) {
			unset($query[$name]);
		} else {
			$query[$name] = $value;
		}
		$newquery = http_build_query($query);
		$parts['query'] = $newquery;
	}
	return http_build_url(array(), $parts);
}

function url_get_query_parameter($url, $name) {
	$parts = parse_url($url);
	if (!isset($parts['query'])) {
		$parts['query'] = '';
	}
	$query = array();
	parse_str($parts['query'], $query);
	return isset($query[$name]) ? $query[$name] : NULL;
}

function url_set_path_parameter($url, $path) {
	$parts = parse_url($url);
	$dir = dirname($_SERVER['REQUEST_URI']);
	$parts['path'] = $dir.'/'.$path;
	$newurl = http_build_url(array(), $parts);
	return $newurl;
}

function url_get_path_parameter($url) {
	$parts = parse_url($url);
	return $parts['path'];
}

function url_relative($path) {
	return url_set_path_parameter(url_full(), $path);
}

function url_redirect($url) {
	if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') {
		if(php_sapi_name() == 'cqi'){
			header('Status: 303 See Other');
		} else {
			header('HTTP/1.1 303 See Other');
		}
	}
	header('Location: '.$url, true, 303);
	die();
}

?>
