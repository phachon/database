<?php
/**
 *
 * @author: panchao
 * Time: 16:27
 */

namespace Database\Type;

use Database\Database;
use Database\Exception;

class Pdo extends Database implements TypeInterface {

	/**
	 * default connection
	 * @var array
	 */
	protected $_defaultConnection = array(
		'dsn'        => '',
		'username'   => NULL,
		'password'   => NULL,
		'persistent' => FALSE,
	);
	
	/**
	 * Pdo constructor.
	 * @param $name
	 * @param $config
	 */
	public function __construct($name, $config) {
		parent::__construct($name, $config);
	}

	/**
	 * connect
	 * @return mixed
	 * @throws Exception
	 */
	public function connect() {
		if ($this->_conn != NULL) {
			return $this;
		}
		
		$connection = $this->_config['connection'] + $this->_defaultConnection;
		extract($connection);

		//错误设置: 内部抛出异常,中断页面
		$options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
		//持久化连接
		if($connection['persistent']) {
			$options[\PDO::ATTR_PERSISTENT] = TRUE;
		}

//		if(!empty($this->_config['charset'])) {
//			$options[\PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES ".$this->_config['charset'];
//		}

		try {
			$this->_conn = new \PDO($dns, $username, $password, $options);
		} catch (\PDOException $e) {
			throw new Exception($e);
		}

		if(!empty($this->_config['charset'])) {
			$this->charset($this->_config['charset']);
		}
	}

	/**
	 * set charset name
	 * @param $charset
	 * @return mixed
	 */
	public function charset($charset) {
		if ($this->_conn == NULL) $this->connect();
		//TODO charset escape()
		$this->_conn->exec("SET NAMES ".$this->_config['charset']);
	}

	/**
	 * execute sql
	 * @param $sql
	 * @return mixed
	 * @throws Exception
	 */
	public function query($sql) {
		if($this->_conn == NULL) $this->connect();
		
		try {
			$mysqlResult = $this->_conn->query($sql);
		}catch (Exception $e) {
			throw new Exception($e);
		}

		$this->_mysqlResult = $mysqlResult;
		$this->_result = $this->_mysqlResult->fetchAll();
	}

	/**
	 * begin transaction
	 * @param null $mode
	 * @return mixed
	 */
	public function begin($mode = NULL) {
		if($this->_conn == NULL) $this->connect();
		$this->_conn->beginTransaction();
	}

	/**
	 * commit sql
	 * @return mixed
	 */
	public function commit() {
		if($this->_conn == NULL) $this->connect();
		$this->_conn->commit();
	}

	/**
	 * rollback
	 * @return mixed
	 */
	public function rollback() {
		if($this->_conn == NULL) $this->connect();
		$this->_conn->rollback();
	}

	/**
	 * escape string
	 * @param $value
	 * @return mixed
	 */
	public function escape($value) {
		if($this->_conn == NULL) $this->connect();
		return $this->_conn->quote($value);
	}

	/**
	 * get affected row
	 * @return mixed
	 */
	public function affectedRows() {
		return $this->_conn->rowCount();
	}

	/**
	 * get insert id
	 * @return mixed
	 */
	public function insertId() {
		return $this->_conn->lastInsertId();
	}

	/**
	 * disconnect
	 */
	public function disconnect() {
		$this->_conn = NULL;
		parent::disconnect();
	}
}