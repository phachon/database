<?php
/**
 *
 * @author: panchao
 * Time: 15:57
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