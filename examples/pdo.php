<?php
/**
 * pdo test
 * @author: phachon@163.com
 */

require_once 'bootstrap.php';

use Database\Database;

$config = array(
	'type' => 'pdo',
	'connection' => array (
		'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8',
		'username' => 'root',
		'password' => '123456',
		'persistent' => FALSE,
	),
	'charset' => 'utf8',
);

//create a db object
$db = Database::instance('default', $config);

// select data return array
$resultArray = $db->query("select * from test_account")
	->as_array();

// select data return object
require_once 'Model/TestModel.php';
$resultObject = $db->query("select * from test_account")
	->as_object("TestModel");

// insert data return insertId
$insertId = $db->query("INSERT INTO test_account (name, given_name, password, phone, mobile, email, status, create_time, update_time) VALUES ('ppkpkp', 'roaadot1', '96e79218965eb72c92a549dd5a330112', '', '', 'phachon@163.com', 0, 1471512945, 1471593345)")
	->insertId();

// update data return affected_rows
$affectedRows = $db->query("UPDATE test_account set mobile=12345167867")
	->affectedRows();

// delete data return affected_rows
$affectedRows = $db->query("DELETE FROM test_account WHERE name='ppkpkp'")
	->affectedRows();

// transaction
$db->begin();
try {
	$db->query("UPDATE test_account set mobile=33221133 WHERE account_id=3");
	$db->query("UPDATE test_account set mobile=311133 WHERE account_id=6");
}catch (Exception $e) {
	$db->rollback();
	exit($e->getMessage());
}
$db->commit(); 