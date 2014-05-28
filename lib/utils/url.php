<?php

require_once 'lib/external/http_build_url.php';

/**
 * This class represents an generic URL which can be created, modified and 
 * converted to strings in various ways.
 * 
 * @author Tobias Oelgarte
 */
class URL {
	
	const STATIC_QUERY_PARAMETER_PREFIX = 's-';
	const DYNAMIC_QUERY_PARAMETER_PREFIX = 'd-';
	
	/** @var boolean */
	private static $HTTPS_ENFORCED = FALSE;
	
	/** @var URL|null */
	private static $ERROR_URL = NULL;
	
	private $scheme;
	private $host;
    private $port;
    private $user;
    private $pass;
    private $path;
	/** @var array|null */
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
	
	public static function create($urlString) {
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
	
	public static function createCurrent() {
		$https      = filter_input(INPUT_SERVER, 'HTTPS');
		$httpHost   = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$requestURI = filter_input(INPUT_SERVER, 'REQUEST_URI');
		$protocol   = $https ? 'https' : 'http';
		if ($httpHost && $requestURI) {
			$urlString = $protocol.'://'.$httpHost.$requestURI;
			return self::create($urlString);
		} else {
			throw new Exception('could note dertermine host or request uri');
		}
	}
	
	/**
	 * 
	 * @param URL|null $url
	 * @return URL
	 */
	public static function createStatic($url=NULL) {
		$u = $url === NULL ? self::createCurrent() : new URL($url);
		$u->removeAllNonStaticQueryParameters();
		return $u;
	}
	
	/**
	 * 
	 * @param URL|null $url
	 * @return URL
	 */	
	public static function createClean($url=NULL) {
		$u = $url === NULL ? self::createCurrent() : new URL($url);
		$u->removeAllQueryParameters();
		return $u;
	}
		
	/**
	 * 
	 * @param string $relativePath
	 * @param URL|null $url
	 * @return URL 
	 */
	public static function createRelative($relativePath, $url = NULL) {
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

	/**
	 * 
	 * @return array|null
	 */
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

	/**
	 * 
	 * @param array|null $query
	 */
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
	 * @deprecated since version 0.1
	 */
	public function getQueryParameter($name) {
		$query = $this->getQuery();
		return $query === NULL ? NULL : isset($query[$name]) ? $query[$name] : NULL;
	}
	
	/**
	 * Sets a query parameter. If a previous query parameter with the same name 
	 * exists it will be overriden and the old value will be returned. Setting 
	 * the value of a query parameter to NULL will remove the existing query 
	 * parameter.
	 * 
	 * Please consider using the preferred static and dynamic functions.
	 * 
	 * @param string $name
	 * @param string|null $value
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
		if ($value === NULL) {
			if ($old !== NULL) {
				unset($this->query[$name]);
				if (count($this->query) == 0) {
					$this->query = NULL;
				}
			}
		} else {
			$this->query[$name] = $value;
		}
		return $old;
	}
	
	/**
	 * Sets a static query parameter. Static query parameters are meant to be 
	 * global query parameters that should be kept while chaning from one 
	 * action to another.
	 * 
	 * @param string $name 
	 *        Name of the parameter, never NULL.
	 * @param string|null $value 
	 *        Value of the parameter. If it is NULL the parameter will be 
	 *        removed.
	 * @return string|null 
	 *         Returns the previously assigned value, or NULL if there was no 
	 *         such parameter set.
	 */
	public function setStaticQueryParameter($name, $value) {
		return $this->setQueryParameter(self::STATIC_QUERY_PARAMETER_PREFIX.$name, $value);
	}
	
	/**
	 * Sets a dynamic query parameter. Dynamic query parameters should be 
	 * omitted when creating an URL with a different action.
	 * 
	 * @param string $name
	 * @param string|null $value
	 * @return string|null
	 */
	public function setDynamicQueryParameter($name, $value) {
		return $this->setQueryParameter(self::DYNAMIC_QUERY_PARAMETER_PREFIX.$name, $value);
	}
	
	/**
	 * Returns the value of the static parameter or NULL if the parameter does 
	 * not exist.
	 * 
	 * @param string $name
	 *        Name of the parameter, never NULL.
	 * @return string|null
	 *         The value of the parameter or NULL if it does not exist.
	 * @see setStaticQueryParameter()
	 */
	public function getStaticQueryParameter($name) {
		return $this->getQueryParameter(self::STATIC_QUERY_PARAMETER_PREFIX.$name);
	}
	
	/**
	 * Returns the value of the dynamic parameter or NULL if the parameter does 
	 * not exist.
	 * 
	 * @param string $name
	 *        Name of the parameter, never NULL.
	 * @return string|null
	 *         The value of the parameter or NULL if it does not exist.
	 * @see setDynamicQueryParameter()
	 */
	public function getDynamicQueryParameter($name) {
		return $this->getQueryParameter(self::DYNAMIC_QUERY_PARAMETER_PREFIX.$name);
	}
	
	public function removeAllQueryParameters($keepPrefix=NULL) {
		if ($keepPrefix === NULL) {
			$this->query = NULL;
		} else if ($this->query !== NULL) {
			foreach ($this->query as $name => $value) {
				if (!StringUtils::startsWith($name, $keepPrefix)) {
					unset($this->query[$name]);
				}
			}
			if (count($this->query) == 0) {
				$this->query = NULL;
			}
		}
	}
		
	public function removeAllNonStaticQueryParameters() {
		$this->removeAllQueryParameters(self::STATIC_QUERY_PARAMETER_PREFIX);
	}
	
	public function removeAllNonDynamicQueryParameters() {
		$this->removeAllQueryParameters(self::DYNAMIC_QUERY_PARAMETER_PREFIX);
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
			if(php_sapi_name() == 'cgi'){
				header('Status: 303 See Other');
			} else {
				header('HTTP/1.1 303 See Other');
			}
		}
		header('Location: '.$this, true, 303);
		die();
	}
	
	public static function redirectToError($message = '') {
		if (self::$ERROR_URL === NULL) {
			$url = URL::createClean();
			$url->setDynamicQueryParameter('action', 'error');
		} else {
			$url = new URL(self::$ERROR_URL);
		}
		$url->setDynamicQueryParameter('message', $message);
		$url->redirect();
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
