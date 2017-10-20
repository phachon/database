<?php
/**
 * Database Result
 * @package  Database\Result
 * @category Object
 * @author   phachon@163.com
 * @copyright (c) 2017 phachon
 * @license  MIT
 */

namespace Database\Result;

use Database\Result;

class Object extends Result implements \Iterator, \Countable, \ArrayAccess, \SeekableIterator {

	protected $_result;

	protected $_currentRow = 0;

	protected $_totalRow = 0;

	protected $_asObject = NULL;

	/**
	 * Object constructor.
	 * @param array $data
	 * @param string $asObject
	 */
	public function __construct(array $data, $asObject = "stdClass") {

		if(is_object($asObject)) {
			$asObject = get_class($asObject);
		}

		if(is_string($asObject)) {
			$className = $asObject;
			$result = array();
			foreach ($data as $item => $row) {
				$resultObj = new $className();
				if(! is_array($row)) {
					if(is_numeric($item)) continue;
					$resultObj->{$key} = $row;
				}else {
					foreach ($row as $key => $value) {
						if(is_numeric($key)) continue;
						$resultObj->{$key} = $value;
					}
				}
				$result[] = $resultObj;
			}
			$this->_asObject = $asObject;
			$this->_result = $result;
		}else {
			$this->_asObject = NULL;
			$this->_result = $data;
		}

		$this->_totalRow = count($data);
		unset($data);
	}

	/**
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	public function current() {
		$this->seek($this->_currentRow);
		return $this->_result[$this->_currentRow];
	}

	/**
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function next() {
		++$this->_currentRow;
	}

	/**
	 * Move forward to prev element
	 */
	public function prev() {
		--$this->_currentRow;
	}

	/**
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 5.0.0
	 */
	public function key() {
		return $this->_currentRow;
	}

	/**
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 * @since 5.0.0
	 */
	public function valid() {
		return $this->offsetExists($this->_currentRow);
	}

	/**
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function rewind() {
		$this->_currentRow = 0;
	}

	/**
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset) {
		return ($offset >=0 && $offset < $this->_totalRow);
	}

	/**
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset) {
		$this->seek($offset);
		return $this->current();
	}

	/**
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value) {
		// TODO: Implement offsetSet() method.
	}

	/**
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset) {
		// TODO: Implement offsetUnset() method.
	}

	/**
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 * @since 5.1.0
	 */
	public function count() {
		return $this->_totalRow;
	}

	/**
	 * Seeks to a position
	 * @link http://php.net/manual/en/seekableiterator.seek.php
	 * @param int $position <p>
	 * The position to seek to.
	 * </p>
	 * @return void
	 * @since 5.1.0
	 */
	public function seek($position) {
		if($this->offsetExists($position) && isset($result[$position])) {
			$this->_currentRow = $position;
		}
	}
}