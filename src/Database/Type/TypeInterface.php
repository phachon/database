<?php
/**
 * Database interface
 * @package  Database
 * @category Database\Interface
 * @author   phachon@163.com
 * @copyright (c) 2017 phachon
 * @license  MIT
 */

namespace Database\Type;


interface TypeInterface {
	
	/**
	 * connect
	 * @return mixed
	 */
	public function connect();

	/**
	 * set charset name
	 * @param $charset
	 * @return mixed
	 */
	public function charset($charset);

	/**
	 * execute sql
	 * @param $sql
	 * @return mixed
	 */
	public function query($sql);

	/**
	 * begin transaction
	 * @param null $mode
	 * @return mixed
	 */
	public function begin($mode = NULL);

	/**
	 * commit sql
	 * @return mixed
	 */
	public function commit();

	/**
	 * rollback
	 * @return mixed
	 */
	public function rollback();

	/**
	 * escape string 
	 * @param $value
	 * @return mixed
	 */
	public function escape($value);

	/**
	 * get affected row
	 * @return mixed
	 */
	public function affectedRows();

	/**
	 * get insert id
	 * @return mixed
	 */
	public function insertId();
}