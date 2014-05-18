<?php

class PageStack extends PageContainer {
	
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
	
}
