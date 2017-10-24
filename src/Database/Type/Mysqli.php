<?php
/**
 * Database Mysqli
 * @package  Database\Type
 * @category Mysqli
 * @author   phachon@163.com
 * @copyright (c) 2017 phachon
 * @license  MIT
 */

namespace Database\Type;

use Database\Database;
use Database\Exception;

class Mysqli extends Database implements TypeInterface {

	/**
	 * default connection
	 * @var array
	 */
	protected $_defaultConnection = array(
		'database' => '',
		'hostname' => '',
		'username' => '',
		'password' => '',
		'socket'   => '',
		'port'     => 3306,
		'ssl'      => NULL,
	);

	/**
	 * default ssl config
	 * @var array
	 */
	protected $_sslConfig = array (
		'key' => '',    //密匙文件的路径名
		'cert' => '',   //证书文件的路径名
		'ca' => '',     //证书颁发机构文件的路径名
		'capath' => '', //路径目录包含受信任的SSL证书PEM格式
		'cipher' => '', //一列允许使用SSL加密的密码
	);
	
	/**
	 * mysqli connect
	 * @throws Exception
	 */
	public function connect() {
		if ($this->_conn != NULL) {
			return $this;
		}
		$connection = $this->_config['connection'] + $this->_defaultConnection;
		extract($connection);
		
		if(is_array($ssl)) {
			//mysql ssl 加密连接
			$this->_conn = mysqli_init();
			$ssl = $ssl + $this->_sslConfig;
			extract($ssl);
			$this->_conn ->ssl_set($key, $cert, $ca, $capath, $cipher);
			$this->_conn->real_connect($hostname, $username, $password, $database, $port, $socket, MYSQLI_CLIENT_SSL);
		}else {
			$this->_conn = @new \mysqli($hostname, $username, $password, $database, $port, $socket);
		}
		
		if($this->_conn->connect_errno) {
			throw new Exception($this->_conn->connect_error, $this->_conn->connect_errno);
		}
		
		//set charset
		if(!empty($this->_config["charset"])) {
			$this->charset($this->_config["charset"]);
		}
		return $this;
	}

	/**
	 * execute sql
	 * @param $sql
	 * @return mixed
	 * @throws Exception
	 */
	public function query($sql) {
		if($this->_conn == NULL) $this->connect();
		
		$sql = trim($sql);
		$mysqlResult = $this->_conn->query($sql);
		if($this->_conn->errno) {
			throw new Exception($this->_conn->error, $this->_conn->errno);
		}
		
		$this->_mysqlResult = $mysqlResult;
		if($this->_mysqlResult instanceof \mysqli_result) {
			$this->_result = $this->_fetchArray();
		}
		
		return $this;
	}

	/**
	 *  事务 begin
	 * @param null $mode mysql 事务的隔离级别
	 * READ UNCOMMITTED：幻读，不可重复读和脏读均允许；
	 * READ COMMITTED：允许幻读和不可重复读，但不允许脏读；
	 * REPEATABLE READ：允许幻读，但不允许不可重复读和脏读；
	 * SERIALIZABLE:幻读，不可重复读和脏读都不允许；
	 * MYSQL默认的是 REPEATABLE READ
	 * @return bool
	 * @throws Exception
	 */
	public function begin($mode = NULL) {
		if($this->_conn == NULL) $this->connect();
		
		//设置 sql 隔离级别
		if($mode != NULL) {
			$this->_conn->query("SET TRANSACTION ISOLATION LEVEL $mode");
			if($this->_conn->errno) {
				throw new Exception($this->_conn->error, $this->_conn->errno);
			}
		}
		return (bool) $this->_conn->query("START TRANSACTION");
	}

	/**
	 *  事务 commit 
	 * @return bool
	 * @throws Exception
	 */
	public function commit() {
		if($this->_conn == NULL) $this->connect();
		return (bool) $this->_conn->query("COMMIT");
	}

	/**
	 * 事务 rollback
	 * @return bool
	 * @throws Exception
	 */
	public function rollback() {
		if($this->_conn == NULL) $this->connect();
		return (bool) $this->_conn->query("ROLLBACK");
	}

	/**
	 * escape
	 * @param $value
	 * @return string
	 * @throws Exception
	 */
	public function escape($value) {
		if($this->_conn == NULL) $this->connect();
		
		$realString = $this->_conn->real_escape_string((string)$value);
		if($this->_conn->errno) {
			throw new Exception($this->_conn->error, $this->_conn->errno);
		}
		return "'.$realString.'";
	}

	/**
	 * set charset name
	 * @param $charset
	 * @return $this
	 * @throws Exception
	 */
	public function charset($charset) {
		if($this->_conn == NULL) $this->connect();
		
		$status = $this->_conn->set_charset($charset);
		if($status === FALSE) {
			throw new Exception($this->_conn->error, $this->_conn->errno);
		}
		return $this;
	}

	/**
	 * affected rows
	 * @return int
	 */
	public function affectedRows() {
		return $this->_mysqlResult ? $this->_conn->affected_rows : 0;
	}
	
	/**
	 * insert_id
	 * @return int
	 */
	public function insertId() {
		return $this->_mysqlResult ? $this->_conn->insert_id : 0;
	}

	/**
	 * return array
	 * @return array
	 */
	protected function _fetchArray() {
		if($this->_mysqlResult->num_rows){
			$list = array();
			while($row = $this->_mysqlResult->fetch_assoc()){
					$list[] = $row;
			}
			$this->_mysqlResult->free();
			return $list;
		}
		return array();
	}

	/**
	 * disconnect
	 * @return bool
	 */
	public function disconnect() {
		$status = TRUE;
		try {
			if($this->_conn && is_resource($this->_conn)) {
				//close conn
				if($this->_conn->close()) {
					//clear conn
					$this->_conn = NULL;
					//clear instance
					parent::disconnect();
				};
			}
		}catch (Exception $e) {
			$status = !is_resource($this->_conn);
		}
		return $status;
	}
}