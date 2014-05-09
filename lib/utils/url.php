<?php

require_once 'lib/external/http_build_url.php';

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
			URL::set($url->scheme, $parts, 'scheme');
			URL::set($url->host, $parts, 'host');
			URL::set($url->port, $parts, 'port');
			URL::set($url->user, $parts, 'user');
			URL::set($url->pass, $parts, 'pass');
			URL::set($url->path, $parts, 'path');
			URL::set($url->query, $parts, 'query');
			URL::set($url->fragment, $parts, 'fragment');
			return $url;
		}
		throw new InvalidArgumentException('not parseable url (url=\''.$urlString.'\'');
	}
	
	public static function urlFromCurrent() {
		$https = filter_input(INPUT_SERVER, 'HTTPS');
		if ($https) {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}
		$httpHost = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$requestURI = filter_input(INPUT_SERVER, 'REQUEST_URI');
		if ($httpHost && $requestURI) {
			$urlString = $protocol.'://'.$httpHost.$requestURI;
			return URL::urlFromString($urlString);
		} else {
			throw new Exception('could note dertermine host or request uri');
		}
	}
	
	/**
	 * 
	 * @param string $relativePath
	 * @param URL|null $url
	 * @return URL 
	 */
	public static function urlFromRelativePath($relativePath, $url = NULL) {
		if ($url === NULL) {
			$url = URL::urlFromCurrent();
		}
		$newUrl = new URL($url);
		$newUrl->setPathRelativeToCurrentPath($relativePath);
		return $newUrl;
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
	
	/**
	 * 
	 * @param string $name
	 * @return string|null
	 */
	public function getQueryParameter($name) {
		$query = $this->getQuery();
		if ($query === NULL) {
			return NULL;
		}
		$a = array();
		parse_str($query, $a);
		return isset($a[$name]) ? $a[$name] : NULL;
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $value
	 * @return string|null
	 * @throws InvalidArgumentException
	 */
	public function setQueryParameter($name, $value) {
		if ($name === NULL) {
			throw new InvalidArgumentException("name can't null");
		}
		$query = $this->getQuery();
		if ($query === NULL) {
			if (!($value === NULL)) {
				$query = array();
				$query[$name] = $value;
				$this->setQuery($query);
			}
			return NULL;
		}
		$a = array();
		parse_str($query, $a);
		$old = isset($a[$name]) ? $a[$name] : NULL;
		if ($value === NULL && !($old === NULL)) {
			unset($a[$name]);
		} else {
			$a[$name] = $value;
		}
		$this->setQuery(http_build_query($a));
		return $old;
	}
	
	public function setPathRelativeToCurrentPath($relativePath) {
		$path = $this->getPath();
		
		// if no path is set assume base path
		if ($path == null) {
			$path = '';
		}
		
		// remove filename and trailing slash at end of path
		$dir = preg_replace('#/[^/]*$#', '', $path);
		
		// append dirty relative path to the end
		$newPath = $dir.'/'.$relativePath;
		
		// replace "/./" or "//" or "foo/../" with "/"
		$regex = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
		for($n=1; $n>0; $newPath=preg_replace($regex, '/', $newPath, -1, $n)) {}
		
		$this->setPath($newPath);
	}
	
	public function redirect() {
		if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') {
			if(php_sapi_name() == 'cqi'){
				header('Status: 303 See Other');
			} else {
				header('HTTP/1.1 303 See Other');
			}
		}
		header('Location: '.$this, true, 303);
		die();
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
