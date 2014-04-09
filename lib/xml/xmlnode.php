<?php

require_once 'lib/xml/xmlleaf.php';

abstract class XMLNode extends XMLLeaf {
	
	/**
	 *
	 * @var ArrayList
	 */
	private $children = NULL;

	/**
	 * 
	 * @param XMLLeaf $child
	 * @param int $index
	 * @return XMLNode
	 */
	public function addChild($child, $index=-1) {
		if ($child === $this) {
			throw new InvalidArgumentException('Cant add element to itself');
		}
		if (is_null($this->children)) {
			$this->children = new ArrayList(); 
		}
		$oldParent = $child->getParent();
		if (!is_null($oldParent)) {
			$oldParent->remove($oldParent->getIndexOfChild($child));
		}
		$this->children->add($child, $index);
		$child->parent = $this;
		return $this;
	}
	
	/**
	 * 
	 * @param int $index
	 * @return XMLLeaf
	 */
	public function removeChild($index) {
		$removed = $this->hasChildren() ? $this->children->remove($index) : NULL;
		if (!is_null($removed)) {
			$removed->parent = NULL;
		}
		return $removed;
	}
	
	/**
	 * 
	 * @param XMLLeaf $index
	 * @return int
	 */
	public function getChild($index) {
		return $this->hasChildren() ? $this->children->get($index) : NULL;
	}
	
	/**
	 * 
	 * @param XMLLeaf $child
	 * @return int
	 */
	public function getIndexOfChild($child) {
		return $this->hasChildren() ? $this->children->indexOf($child) : -1;
	}
	
	/**
	 * 
	 * @return bool
	 */
	public function hasChildren() {
		return !is_null($this->children);
	}
	
	/**
	 * 
	 * @return int
	 */
	public function getChildCount() {
		return $this->hasChildren() ? $this->children->size() : 0;
	}
	
	/**
	 * 
	 * @return ArrayList
	 */
	public function getChildren() {
		return new ArrayList($this->children);
	}
	
}
