<?php
/**
 * Database
 * @package  Database
 * @category Result
 * @author   phachon@163.com
 * @copyright (c) 2017 phachon
 * @license  MIT
 */

namespace Database;

abstract class Result {

	const RESULT_OBJECT = 1;
	const RESULT_ARRAY = 2;

	public static function factory(array $result, $resultType = 0, $asObject = NULL) {
		if($resultType == self::RESULT_OBJECT) {
			return new Result\Object($result, $asObject);
		}
		if($resultType == self::RESULT_ARRAY) {
			return $result;
		}
		
		throw new Exception("Unsupported database result type");
	}
}