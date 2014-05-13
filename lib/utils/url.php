<?php

require_once 'lib/external/http_build_url.php';

class URL {
	
	private static $BASE_URL = NULL;
	private static $HTTPS_ENFORCED = FALSE;
	
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

	private static function set(&$var, $parts, $name, $parse=FALSE) {
		if ($parse) {
			if (isset($parts[$name])) {
				$var = array();
				parse_str($parts[$name], $var);
			} else {
				$var = NULL;
			}
		} else {
			$var = isset($parts[$name]) ? $parts[$name] : NULL;
		}
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
			URL::set($url->query, $parts, 'query', TRUE);
			URL::set($url->fragment, $parts, 'fragment');
			return $url;
		}
		throw new InvalidArgumentException('not parseable url (url=\''.$urlString.'\'');
	}
	
	/**
	 * 
	 * @return URL
	 * @throws Exception
	 */
	public static function urlFromCurrent() {
		$https      = filter_input(INPUT_SERVER, 'HTTPS');
		$httpHost   = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$requestURI = filter_input(INPUT_SERVER, 'REQUEST_URI');
		$protocol   = $https ? 'https' : 'http';
		if ($httpHost && $requestURI) {
			$urlString = $protocol.'://'.$httpHost.$requestURI;
			return URL::urlFromString($urlString);
		} else {
			throw new Exception('could note dertermine host or request uri');
		}
	}
	
	public static function urlFromBase() {
		return self::urlFromString(self::$BASE_URL);
	}
	
	public static function setBasePath($basePath) {
		$https    = filter_input(INPUT_SERVER, 'HTTPS');
		$httpHost = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$protocol = $https ? 'https' : 'http';
		self::$BASE_URL = $protocol.'://'.$httpHost.'/'.$basePath.'/';
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
	
	public static function setHttpsEnforced($enforced) {
		self::$HTTPS_ENFORCED = $enforced;
	}
	
	public static function isHttpsEnforced() {
		return self::$HTTPS_ENFORCED;
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
		return $query === NULL ? NULL : isset($query[$name]) ? $query[$name] : NULL;
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
		if ($this->query === NULL) {
			if ($value !== NULL) {
				$this->query = array();
				$this->query[$name] = $value;
			}
			return NULL;
		}
		$old = isset($this->query[$name]) ? $this->query[$name] : NULL;
		if ($value === NULL && $old !== NULL) {
			unset($this->query[$name]);
			if (count($this->query) == 0) {
				$this->query = NULL;
			}
		} else {
			$this->query[$name] = $value;
		}
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
		$parts['scheme'] = self::$HTTPS_ENFORCED ? 'https' : $this->scheme;
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
