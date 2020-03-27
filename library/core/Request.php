<?php
/**
 * Author: 大眼猫
 * File: Request.php
 */

class Request {

	private static $fd;
	private static $data;
	private static $page;
	private static $instance;

	public static function getFd(){
		return self::$fd;
	}

	public static function setFd($fd){
		self::$fd = $fd;
	}

	public static function has($key){
		return isset(self::$data[$key]);
	}

	public static function get($key){
		if(isset(self::$data[$key])){
			return self::$data[$key];
		}else{
			return NULL;
		}
	}

	public static function set($key, $val){
		self::$data[$key] = $val;
	}

	public static function setInstance($instance){
		self::$instance = $instance;
	}

	public static function getInstance(){
		return self::$instance;
	}

	public static function getPage(){
		return self::$page;
	}

	public static function setPage($page){
		self::$page = $page;
	}

	public static function setData($data){
		self::$data = $data;
	}

	public static function getData(){
		return self::$data;
	}

}