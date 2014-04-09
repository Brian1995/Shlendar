<?php

class ArrayList implements IteratorAggregate {

	private $data = array();
	private $size = 0;

	public function __construct(ArrayList $list=NULL) {
		if (!is_null($list)) {
			$this->data = $list->data;
			$this->size = $list->size;
		}
	}
	
	private function checkIndex($min, $max, $index) {
		if ($min > $index || $index > $max) {
			throw new InvalidArgumentException('index out of bounds (min=' . $min . ', max=' . $max . ', index=' . $index . ')');
		}
	}

	public function add($object, $index = -1) {
		$this->checkIndex(-1, $this->size, $index);
		if ($index == -1) {
			$index = $this->size;
		}
		$tmp = array();
		$tmp[0] = $object;
		array_splice($this->data, $index, 0, $tmp);
		$this->size++;
	}
	
	public function get($index) {
		$this->checkIndex(0, $this->size, $index);
		return $this->data[$index];
	}
	
	public function size() {
		return $this->size;
	}

	public function indexOf($object) {
		$result = array_search($object, $this->data, true);
		if ($result === FALSE) {
			return -1;
		}
		return $result;
	}
	
	/**
	 * 
	 * @param integer $index The index of the object that should be removed.
	 * @return The removed object.
	 */
	public function remove($index) {
		$this->checkIndex(0, $this->size-1, $index);
		$removed = array_splice($this->data, $index, 1);
		$this->size--;
		return reset($removed);
	}
	
	public function clear() {
		$this->data = array();
		$this->size = 0;
	}
	
	public function isEmpty() {
		return $this->size == 0;
	}
	
	public function getIterator() {
		return new ArrayListIterator($this);
	}
	
	public function __toString() {
		$s = 'ArrayList [';
		for ($i=0; $i<$this->size-1; $i++) {
			$s .= $this->data[$i];
			$s .= ', ';
		}
		if ($this->size > 0) {
			$s .= $this->data[$this->size-1];
		}
		$s .= ']';
		return $s;
	}
	
}

class ArrayListIterator implements Iterator {
	
	/**
	 * @var int
	 */
	private $index = 0;
	
	/**
	 * @var ArrayList
	 */
	private $list;
	
	function __construct($list) {
		$this->list = $list;
	}
	
	public function current() {
		return $this->list->get($this->index);
	}

	public function key() {
		return $this->index;
	}

	public function next() {
		$this->index += 1;
	}

	public function rewind() {
		$this->index = 0;
	}

	public function valid() {
		return $this->index >= 0 && $this->index < $this->list->size();
	}
	
}
