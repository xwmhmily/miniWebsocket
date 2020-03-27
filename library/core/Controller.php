<?php
/**
 * File: Controller.php
 * Author: 大眼猫
 */

abstract class Controller {

	protected function getParam($key, $filter = TRUE){
		$value = Request::get($key);
		if($filter){
			$value = Security::filter($value);
		}

		return $value;
	}

	// 中间件
	protected function middleware($middleware){
		try{
			(new Pipeline)->send()->through($middleware)->via('handle')->then(function(){
				Response::setMiddlewareStatus(TRUE);
			});
		}catch (Throwable $e){
			Response::setMiddlewareStatus(FALSE);

			$error = [];
			$error['code']  = $e->getCode();
			$error['error'] = $e->getMessage();
			Response::setMiddlewareError(JSON($error));
		}
	}

	// 加载模型
	protected function load($model){
		return Helper::load($model);
	}

	public function __call($name, $arguments){
		$rep['code']  = 0;
		$rep['error'] = 'Method '.$name.' not found';
		return JSON($rep);
	}
	
}