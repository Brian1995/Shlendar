<?php

class XMLPrinter {
	
	private $indent;
	private $linebreak;
	private $noShortCloseList;
	
	function __construct($indent="\t", $linebreak="\r\n", $noShortCloseList=NULL) {
		$this->indent = $indent;
		$this->linebreak = $linebreak;
		if ($noShortCloseList === NULL) {
			$this->noShortCloseList = array('div', 'span', 'a', 'i', 'b', 'u', 'em', 'script', 'form');
		} else {
			$this->noShortCloseList = $noShortCloseList;
		}
	}
	
	public function getIndent() {
		return $this->indent;
	}

	public function setIndent($indent) {
		$this->indent = $indent;
	}

	public function getLinebreak() {
		return $this->linebreak;
	}

	public function setLinebreak($linebreak) {
		$this->linebreak = $linebreak;
	}

	/**
	 * 
	 * @param XMLLeaf $leaf
	 * @throws InvalidArgumentException
	 */
	public function createString($leaf) {
		if (is_null($leaf)) {
			throw new InvalidArgumentException('leaf can´t be null');
		}
		$s = '';
		$indent = '';
		return $this->createXML($leaf, $s, $indent);
	}

	/**
	 * 
	 * @param XMLLeaf $leaf
	 * @param string $s
	 * @param string $indent
	 * @return string
	 * @throws InvalidArgumentException
	 */
	private function createXML($leaf, &$s, &$indent) {
		if ($leaf instanceof XMLElement) {
			/** @param XMLElement $element */
			$element = $leaf;
			$s .= $indent.'<'.$element->getName();
			foreach ($element->getAttributes() as $a) {
				$s .= ' '.$a;
			}
			if ($element->hasChildren()) {
				$first = $element->getChild(0);
				$s .= '>';
				if ($element->getChildCount() == 1 && $first instanceof XMLText) {
					/** @param XMLText $text */
					$text = $first;
					$s .= $text->getData();
				} else {
					$s .= $this->linebreak;
					$indent .= $this->indent;
					foreach ($element->getChildren() as $child) {
						$this->createXML($child, $s, $indent);
					}
					$indent = substr($indent, 0, -strlen($this->indent));
					$s .= $indent;
				}
				$s .= '</'.$element->getName().'>';
			} else if (in_array($element->getName(), $this->noShortCloseList)) {
				$s .= '></'.$element->getName().'>';
			} else {
				$s .= ' />';
			}
			$s .= $this->linebreak;
		} else if ($leaf instanceof XMLText) {
			/** @param XMLText $text */
			$text = $leaf;
			$s .= $text->getData();
		} else {
			throw new InvalidArgumentException("unknown data type");
		}
		return $s;
	}

}
