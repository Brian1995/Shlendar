<?php

/**
 * Base class for all page elements.
 */
abstract class PageElement {
	
	/**
	 * The name of the tag inside the xml representation.
	 * @var string|null
	 */
	protected $elementType = NULL;

	/**
	 * An array that gets initialized if at least one attribute is set. If the 
	 * element does not have any attributes this member will be NULL.
	 * @var array
	 */
	protected $properties = NULL;
	
	/**
	 * Constructs a new page element. To be able to convert this element to an 
	 * xml representation with toXML() an element name must be given or the 
	 * method toXML() must be overriden.
	 * 
	 * @param string|null $elementName
	 */
	public function __construct($elementName=NULL) {
		$this->elementType = $elementName;
	}
	
	/**
	 * 
	 * @return string|null
	 */
	public function getElementType() {
		return $this->elementType;
	}

	/**
	 * 
	 * @param string|null $elementType
	 * @return PageElement
	 */
	public function setElementType($elementType) {
		$this->elementType = $elementType;
		return $this;
	}

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
	
	public function setProperties() {
		$argumentCount = func_num_args();
		$arguments = func_get_args();
		for ($i = 0; $i < $argumentCount; $i += 2) {
			$this->setProperty($arguments[$i], $arguments[$i+1]);
		}
		return $this;
	}
	
	/**
	 * Adds all properties from this PageElement as attributes to xmlElement.
	 * 
	 * If getIncludeList() returns a string array, only named attributes from 
	 * that list will be taken into account.
	 * 
	 * If getExcludeList() returns a string array, all named attributes from 
	 * that list will be ignored.
	 * 
	 * @param XMLElement $xmlElement
	 *        The XMLElement to add the attributes to.
	 */
	protected function addAttributesToXMLElement(XMLElement &$xmlElement) {
		$includeList = $this->getIncludeList();
		$excludeList = $this->getExcludeList();
		if ($includeList === NULL) {
			$properties = $this->getProperties();
		} else {
			$properties = array_filter($this->getProperties(), function($p) use (&$includeList) { return in_array($p, $includeList); });
		}
		if ($excludeList === NULL) {
			foreach ($properties as $name => $value) {
				$xmlElement->setAttribute($name, $value);
			}
		} else {
			foreach ($properties as $name => $value) {
				if (!in_array($name, $excludeList)) {
					$xmlElement->setAttribute($name, $value);
				}
			}
		}
	}
	
	/**
	 * Overide this method to only include certain properties as attributes to 
	 * the xml representation.
	 * 
	 * @return array|null 
	 *         A string array containing the names of the properties 
	 *         that should be added as attributes, or NULL if all properties 
	 *         should be added (default).
	 */
	protected function getIncludeList() {
		return NULL;
	}
	
	/**
	 * Overide this method to exclude the export of certain properties as 
	 * attributes to the xml representation.
	 * 
	 * @return array|null 
	 *         A string array containing the names of the properties 
	 *         that should be left out as attributes, or NULL if no property 
	 *         should be ignored (default).
	 */
	protected function getExcludeList() {
		return NULL;
	}
	
	/**
	 * 
	 * @return XMLElement
	 */
	public function toXML() {
		$elementType = $this->getElementType();
		if ($elementType === NULL) {
			throw new Exception("Can't convert element to xml element without a element name.");
		}
		$element = new XMLElement($elementType);
		$this->addAttributesToXMLElement($element);
		return $element;
	}

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

	public function __construct($elementName=NULL) {
		parent::__construct($elementName);
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
	
	/**
	 * Returns TRUE if this container has at least one child element, FALSE 
	 * otherwise.
	 * 
	 * @return boolean 
	 *         TRUE if this container has at least one child element, FALSE 
	 *         otherwise.
	 */
	public function hasChildren() {
		return $this->getChildCount() > 0;
	}

	public function toXML() {
		$element = parent::toXML();
		for ($i = 0, $count = $this->getChildCount(); $i < $count; $i++) {
			$element->addChild($this->getChild($i)->toXML());
		}
		return $element;
	}

}
