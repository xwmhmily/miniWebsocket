<?php
/**
 * Author: 大眼猫
 * File: HTTP Response.php
 */

class Response {

	private static $instance;
	private static $middleware_error;
	private static $middleware_status;

	public static function getInstance(){
		return self::$instance;
	}

	public static function setInstance($instance){
		self::$instance = $instance;
	}

	public static function setMiddlewareStatus($status){
		self::$middleware_status = $status;
	}

	public static function getMiddlewareStatus(){
		return self::$middleware_status;
	}

	public static function setMiddlewareError($error){
		self::$middleware_error = $error;
	}

	public static function getMiddlewareError(){
		return self::$middleware_error;
	}

	public static function endByMiddleware(){
		Response::setMiddlewareStatus(TRUE);
		$error = self::getMiddlewareError();
		return self::send($error);
	}

	public static function send($output){
		return self::getInstance()->push(Request::getFd(), $output);
	}

}