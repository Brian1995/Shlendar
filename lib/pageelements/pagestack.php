<?php

class PageStack extends PageContainer {
	
	/** @var string */
	private $elementType;
	
	/**
	 *
	 * @param string $elementType
	 */
	function __construct($elementType='div') {
		parent::__construct();
		$this->setElementType($elementType);
		$argumentCount = func_num_args();
		$arguments = func_get_args();
		for ($i = 1; $i < $argumentCount; $i += 2) {
			$attributeName = $arguments[$i];
			$attributeValue = $arguments[$i+1];
			$this->addAttribute($attributeName, $attributeValue);
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getElementType() {
		return $this->elementType;
	}

	/**
	 * 
	 * @param string $elementType
	 * @return PageStack
	 */
	public function setElementType($elementType) {
		$this->elementType = $elementType;
		return $this;
	}
	
	/**
	 * 
	 * @return XMLElement
	 */
	public function toXML() {
		$element = new XMLElement($this->elementType);
		self::addAttributesToXMLElement($element, $this);
		for ($i = 0, $count = $this->getChildCount(); $i < $count; $i++) {
			$element->addChild($this->getChild($i)->toXML());
		}
		return $element;
	}

}
