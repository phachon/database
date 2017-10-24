<?php
/**
 * Database interface
 * @package  Database
 * @category Interface
 * @author   phachon@163.com
 * @copyright (c) 2017 phachon
 * @license  MIT
 */

namespace Database;


interface DatabaseInterface {
	
	/**
	 * instance
	 * @param null $name
	 * @param array $config
	 * @return mixed
	 */
	public static function instance($name = NULL, array $config = array());

	/**
	 * Database constructor.
	 * @param $name
	 * @param $config
	 */
	public function __construct($name, $config);

	/**
	 * disconnect
	 * @return bool
	 */
	public function disconnect();

	/**
	 * 处理输入数据
	 * @param $value
	 * @return mixed
	 */
	public function valid($value);
	
	/**
	 * as_array
	 * @param null $key
	 * @return array
	 */
	public function as_array($key = NULL);

	/**
	 * as_object
	 * @param null $asObject
	 * @return Result\Object
	 * @throws Exception
	 */
	public function as_object($asObject = NULL);
}