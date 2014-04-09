<?php

require_once 'lib/xml/xmlleaf.php';
require_once 'lib/utils.php';

class XMLText extends XMLLeaf {
	
	/**
	 *
	 * @var string
	 */
	private $data;
	
	/**
	 * 
	 * @param string $data
	 */
	function __construct($data) {
		$this->data = is_null($data) ? '' : StringUtils::escapeHTML($data);
	}
	
	public function getData() {
		return $this->data;
	}

}
