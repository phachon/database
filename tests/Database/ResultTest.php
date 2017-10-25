<?php
/**
 * Result Test
 * @author: phachon@163.com
 */

namespace Database;


use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase {

	/**
	 * test result array
	 */
	public function testResultArray() {
		$data = array(
			'name' => 'ok'
		);
		$result = Result::factory($data, Result::RESULT_ARRAY);
		
		$this->assertEquals($data, $result);
	}

	/**
	 * test result object
	 * @throws Exception
	 */
	public function testResultObject() {
		require 'tests/Model/TestModel.php';
		$data = array(
			array ('name' => 'ok')
		);
		$result = Result::factory($data, Result::RESULT_OBJECT, "TestModel");
		
		$this->assertEquals("Database\\Result\\Object", get_class($result));
	}
}