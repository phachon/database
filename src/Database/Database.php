<?php
/**
 * Database
 * @package  Database
 * @category Database
 * @author   phachon@163.com
 * @copyright (c) 2017 phachon
 * @license  MIT
 */

namespace Database;

abstract class Database implements DatabaseInterface {

	/**
	 * instances
	 * @var array
	 */
	protected static $_instances = array();

	/**
	 * connection
	 * @var
	 */
	protected $_conn = NULL;

	/**
	 * name
	 * @var string
	 */
	protected $_name = "";

	/**
	 * config
	 * @var array
	 */
	protected $_config = array();

	/**
	 * mysql result
	 */
	protected $_mysqlResult = FALSE;

	/**
	 * select result
	 * @var array
	 */
	protected $_result = array();

	/**
	 * instance
	 * @param null $name
	 * @param array $config
	 * @return mixed
	 */
	public static function instance($name = NULL, array $config = array()) {
		if(!isset(self::$_instances[$name])) {
			$driver = ucfirst(strtolower($config['type']));
			$className = "Database\\Type\\{$driver}";
			self::$_instances[$name] = new $className($name, $config);
		}

		return self::$_instances[$name];
	}

	/**
	 * Database constructor.
	 * @param $name
	 * @param $config
	 */
	public function __construct($name, $config) {
		$this->_name = $name;
		$this->_config = $config;
	}

	/**
	 * disconnect
	 * @return bool
	 */
	public function disconnect() {
		unset(self::$_instances[$this->_name]);
	}
	
	/**
	 * as_array
	 * @param null $key
	 * @return array
	 */
	public function as_array($key = NULL) {
		$result = Result::factory($this->_result, Result::RESULT_ARRAY);
		if($key == NULL) {
			return $result;
		}
		$keyResult = array();
		foreach ($result as $row) {
			$keyResult[] = array($key, $row[$key]);
		}
		return $keyResult;
	}

	/**
	 * as_object
	 * @param null $asObject
	 * @return Result\Object
	 * @throws Exception
	 */
	public function as_object($asObject = NULL) {
		return Result::factory($this->_result, Result::RESULT_OBJECT, $asObject);
	}

	/**
	 * destruct
	 */
	public function __destruct() {
		$this->disconnect();
	}

	/**
	 * @return mixed
	 */
	public function __toString() {
		return $this->_name;
	}
}