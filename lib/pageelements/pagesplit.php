<?php

class PageSplit extends PageElement {
	
	/** @var PageElement|null */
	private $left;
	/** @var PageElement|null */
	private $center;
	/** @var PageElement|null */
	private $right;
	
	/**
	 * 
	 * @return PageElement|null
	 */
	public function getLeft() {
		return $this->left;
	}

	/**
	 * 
	 * @param PageElement|null $left
	 * @return \PageSplit
	 */
	public function setLeft($left) {
		$this->left = $left;
		return $this;
	}

	/**
	 * 
	 * @return PageElement|null
	 */
	public function getCenter() {
		return $this->center;
	}

	/**
	 * 
	 * @param PageElement|null $center
	 * @return \PageSplit
	 */
	public function setCenter($center) {
		$this->center = $center;
		return $this;
	}

	/**
	 * 
	 * @return PageElement|null
	 */
	public function getRight() {
		return $this->right;
	}

	/**
	 * 
	 * @param PageElement|null $right
	 * @return \PageSplit
	 */
	public function setRight($right) {
		$this->right = $right;
		return $this;
	}

	public function toXML() {
		$table = new XMLElement('div');
		$properties = $this->getProperties();
		foreach ($properties as $name => $value) {
			$table->addAttribute($name, $value);
		}
		$table->addChild($row = new XMLElement('div'));
		if ($this->left !== NULL) {
			$row->addChild($left = new XMLElement('div', 'class', 'left'));
			$left->addChild($this->left->toXML());
		}
		if ($this->center !== NULL) {
			$row->addChild($center = new XMLElement('div', 'class', 'center'));
			$center->addChild($this->center->toXML());
		}
		if ($this->right !== NULL) {
			$row->addChild($right = new XMLElement('div', 'class', 'right'));
			$right->addChild($this->right->toXML());
		}
		return $table;
	}

}
