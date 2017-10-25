<?php
/**
 * Database Test
 * @author: phachon@163.com
 */

namespace Database;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase {

	protected function getInstance($name, $config) {
		return Database::instance($name, $config);
	}

	/**
	 * test database implement interface
	 */
	public function testImplementDatabaseInterface() {
		$this->assertNotFalse("Database\\Database", "Database\\DatabaseInterface");
	}

	/**
	 * @covers Database\Database::instance
	 */
	public function testInstance() {
		
		$config['type'] = 'mysqli';
		
		$instance = $this->getInstance('default', $config);
		$className = get_class($instance);
		$this->assertEquals("Database\\Type\\Mysqli", $className);
		
		$instance1 = $this->getInstance('default', $config);
		$this->assertEquals($instance, $instance1);

		$config['type'] = 'pdo';
		$instance2 = $this->getInstance('default1', $config);
		$className2 = get_class($instance2);
		$this->assertEquals("Database\\Type\\Pdo", $className2);
		
	}

	/**
	 * @covers Database\Database::__construct
	 */
	public function testConstruct() {
		$name = "default";
		$config = array(
			"type" => 'mysqli',
		);
		$instance = $this->getInstance($name, $config);
		
		$this->assertAttributeEquals($name, "_name", $instance);
		$this->assertAttributeEquals($config, "_config", $instance);
	}
}