<?php

/**
 * Base class for all page elements.
 */
abstract class Element {

	/**
	 * An array that gets initialized if at least one attribute is set. If the 
	 * element does not have any attributes this member will be NULL.
	 * @var array
	 */
	private $attributes = NULL;

	private function removeAttribute($name) {
		$old = NULL;
		if (!is_null($this->attributes)) {
			if (isset($this->attributes[$name])) {
				$old = $this->attributes[$name];
				unset($this->attributes[$name]);
			}
			if (count($this->attributes) == 0) {
				$this->attributes = NULL;
			}
		}
		return $old;
	}
	
	private function addAttribute($name, $value) {
		$old = NULL;
		if (is_null($this->attributes)) {
			$this->attributes = array();
		}
		if (isset($this->attributes[$name])) {
			$old = $this->attributes[$name];
		}
		$this->attributes[$name] = $value;
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
	public function setAttribute($name, $value) {
		if (is_null($name)) {
			throw new InvalidArgumentException('"name" can´t be null');
		}
		if (is_null($value)) {
			return $this->removeAttribute($name);
		} else {
			return $this->addAttribute($name, $value);
		}
	}

	/**
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name) {
		if (is_null($this->attributes)) {
			return NULL;
		}
		return isset($this->attributes[$name]) ? $this->attributes[$name] : NULL;
	}

	/**
	 * 
	 * @return array
	 */
	public function getAttributes() {
		return is_null($this->attributes) ? array() : $this->attributes;
	}

	/**
	 * 
	 * @return string
	 */
	abstract function toHTML();

	/**
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->toHTML();
	}

}

/**
 * Base class for alle page elements that can contain other page elements.
 */
abstract class ElementContainer extends Element {

	private $children;

	public function __construct() {
		$this->children = new ArrayList();
	}

	public function addChild(Element $element, $index = -1) {
		$this->children->add($element, $index);
	}

	public function getChild($index) {
		return $this->children->get($index);
	}

	public function removeChild($index) {
		return $this->children->remove($index);
	}

	public function getChildCount() {
		return $this->children->size();
	}

}
