<?php

include_once 'lib/external/http_build_url.php';

class URL {
	
	private $scheme;
	private $host;
    private $port;
    private $user;
    private $pass;
    private $path;
    private $query;
    private $fragment;

	public function __construct(URL $url=NULL) {
		if (!is_null($url)) {
			$this->scheme = $url->scheme;
			$this->host = $url->host;
			$this->port = $url->port;
			$this->user = $url->user;
			$this->path = $url->path;
			$this->query = $url->query;
			$this->fragment = $url->fragment;
		}
	}

	private static function set(&$var, $parts, $name) {
		$var = isset($parts[$name]) ? $parts[$name] : NULL;
	}
	
	public static function urlFromString($urlString) {
		$url = new URL();
		$parts = parse_url($urlString);
		if ($parts) {
			$this->set($url->scheme, $parts, 'scheme');
			$this->set($url->host, $parts, 'host');
			$this->set($url->port, $parts, 'port');
			$this->set($url->user, $parts, 'user');
			$this->set($url->pass, $parts, 'pass');
			$this->set($url->path, $parts, 'path');
			$this->set($url->query, $parts, 'query');
			$this->set($url->fragment, $parts, 'fragment');
			return $url;
		}
		throw new InvalidArgumentException('not parseable url (url=\''.$urlString.'\'');
	}
	
	public static function urlFromCurrent() {
		$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
		$urlString = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		return URL::urlFromString($urlString);
	}
	
	public function getScheme() {
		return $this->scheme;
	}

	public function getHost() {
		return $this->host;
	}

	public function getPort() {
		return $this->port;
	}

	public function getUser() {
		return $this->user;
	}

	public function getPass() {
		return $this->pass;
	}

	public function getPath() {
		return $this->path;
	}

	public function getQuery() {
		return $this->query;
	}

	public function getFragment() {
		return $this->fragment;
	}

	public function setScheme($scheme) {
		$this->scheme = $scheme;
	}

	public function setHost($host) {
		$this->host = $host;
	}

	public function setPort($port) {
		$this->port = $port;
	}

	public function setUser($user) {
		$this->user = $user;
	}

	public function setPass($pass) {
		$this->pass = $pass;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function setQuery($query) {
		$this->query = $query;
	}

	public function setFragment($fragment) {
		$this->fragment = $fragment;
	}

	public function __toString() {
		$parts = array();
		$parts['scheme'] = $this->scheme;
		$parts['host'] = $this->host;
		$parts['port'] = $this->port;
		$parts['user'] = $this->user;
		$parts['pass'] = $this->pass;
		$parts['path'] = $this->path;		
		$parts['query'] = $this->query;
		$parts['fragment'] = $this->fragment;
		return http_build_url(array(), $parts);
	}
}
