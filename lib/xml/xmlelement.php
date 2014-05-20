<?php

require_once 'lib/utils.php';
require_once 'lib/xml/xmlnode.php';

class XMLElement extends XMLNode {
	
	/**
	 * @var string
	 */
	private $name;
	
	/**
	 * @var array
	 */
	private $attributes = NULL;
		
	public function __construct($name) {
		$this->name = $name;
		$argumentCount = func_num_args();
		$arguments = func_get_args();
		for ($i = 1; $i < $argumentCount; $i += 2) {
			$attributeName = $arguments[$i];
			$attributeValue = $arguments[$i+1];
			$this->addAttribute($attributeName, $attributeValue);
		}
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function removeAttribute($name) {
		if (!is_null($this->attributes)) {
			unset($this->attributes[$name]);
			if (count($this->attributes) == 0) {
				$this->attributes = NULL;
			}
		}
		return $this;
	}
	
	public function addAttribute($name, $value) {
		if (is_null($this->attributes)) {
			$this->attributes = array();
		}
		$this->attributes[$name] = new XMLAttribute($name, $value);
		return $this;
	}
	
	/**
	 * Sets an attribute with the given name and value.
	 * If the value is NULL the attribute will be unset/removed. If there was 
	 * an previously assigned attribute with the same name, then the value of 
	 * that attribute will be returned. Otherwise the return value will be 
	 * NULL.
	 * 
	 * @param string $name The attribute name.
	 * @param XMLAttribute $value The attribute value.
	 * @return XMLAttribute The previous value of the attribute if it has been 
	 *         set or NULL.
	 * @throws InvalidArgumentException Will be thrown if the name parameter is 
	 *         NULL.
	 */
	public function setAttribute($name, $value) {
		if (is_null($name)) {
			throw new InvalidArgumentException('"name" can´t be null');
		}
		if (is_null($value)) {
			$this->removeAttribute($name);
		} else {
			$this->addAttribute($name, $value);
		}
		return $this;
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
		return isset($this->attributes[$name]) ? $this->attributes[$name]->getValue() : NULL;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getAttributes() {
		return is_null($this->attributes) ? array() : $this->attributes;
	}
	
	public function getAttributeCount() {
		return $this->hasAttributes() ? count($this->attributes) : 0;
	}
	
	public function hasAttributes() {
		return !is_null($this->attributes);
	}

}
