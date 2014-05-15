<?php

/**
 * Base class for all page elements.
 */
abstract class PageElement {

	/**
	 * An array that gets initialized if at least one attribute is set. If the 
	 * element does not have any attributes this member will be NULL.
	 * @var array
	 */
	private $properties = NULL;

	private function removeProperty($name) {
		$old = NULL;
		if ($this->properties !== null) {
			if (isset($this->properties[$name])) {
				$old = $this->properties[$name];
				unset($this->properties[$name]);
			}
			if (count($this->properties) == 0) {
				$this->properties = NULL;
			}
		}
		return $old;
	}
	
	private function addProperty($name, $value) {
		$old = NULL;
		if ($this->properties === NULL) {
			$this->properties = array();
		}
		if (isset($this->properties[$name])) {
			$old = $this->properties[$name];
		}
		$this->properties[$name] = $value;
		return $old;
	}
	
	/**
	 * Sets an attribute with the given name and value. The name can be 
	 * anything except NULL. If the value is NULL the attribute will be 
	 * unset/removed. If there was an previously assigned attribute with the 
	 * same name, then the value of that attribute will be returned. Otherwise 
	 * the return value will be NULL.
	 * 
	 * @param string $name The attribute name.
	 * @param mixed $value The attribute value.
	 * @return mixed The previous value of the attribute if it has been set or 
	 *         NULL.
	 * @throws InvalidArgumentException Will be thrown if the name parameter is 
	 *         NULL.
	 */
	public function setProperty($name, $value) {
		if ($name === NULL) {
			throw new InvalidArgumentException('"name" can´t be null');
		}
		if ($value === NULL) {
			return $this->removeProperty($name);
		} else {
			return $this->addProperty($name, $value);
		}
	}

	/**
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function getProperty($name) {
		if ($this->properties === NULL) {
			return NULL;
		}
		return isset($this->properties[$name]) ? $this->properties[$name] : NULL;
	}

	/**
	 * 
	 * @return array
	 */
	public function getProperties() {
		return $this->properties === NULL ? array() : $this->properties;
	}
	
	/**
	 * Adds all properties from pageElement as attributes to xmlElement.
	 * 
	 * If includeList is present, only named attributes from that list will 
	 * be taken into account.
	 * 
	 * If excludeList is present, all named attributes from that list will 
	 * be ignored.
	 * 
	 * @param XMLElement $xmlElement
	 *        The XMLElement to add the attributes to.
	 * @param PageElement $pageElement 
	 *        The PageElement from which the properties are taken.
	 * @param array $includeList
	 *        A string array containing the names of the attributes that might 
	 *        be converted to attributes.
	 * @param array $excludeList
	 *        A string array containing the names of the attributes that will 
	 *        not be converted to attributes.
	 */
	public static function addAttributesToXMLElement(XMLElement &$xmlElement, PageElement &$pageElement, array $includeList=NULL, array $excludeList=NULL) {
		if ($includeList === NULL) {
			$properties = $pageElement->getProperties();
		} else {
			$properties = array_filter($pageElement->getProperties(), function($p) use (&$includeList) { return in_array($p, $includeList); });
		}
		foreach ($properties as $name => $value) {
			if (!in_array($name, $excludeList)) {
				$xmlElement->setAttribute($name, $value);
			}
		}
	}

	/**
	 * 
	 * @return XMLElement
	 */
	abstract function toXML();

}

/**
 * Base class for alle page elements that can contain other page elements.
 */
abstract class PageContainer extends PageElement {

	/**
	 *
	 * @var ArrayList
	 */
	protected $children;

	public function __construct() {
		$this->children = new ArrayList();
	}

	/**
	 * 
	 * @param PageElement $element
	 * @param integer $index
	 */
	public function addChild($element, $index = -1) {
		$this->children->add($element, $index);
	}

	/**
	 * 
	 * @param integer $index
	 * @return PageElement
	 */
	public function getChild($index) {
		return $this->children->get($index);
	}

	/**
	 * 
	 * @param integer $index
	 * @return PageElement
	 */
	public function removeChild($index) {
		return $this->children->remove($index);
	}

	/**
	 * 
	 * @return integer
	 */
	public function getChildCount() {
		return $this->children->size();
	}

}
