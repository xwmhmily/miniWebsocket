<?php

class Worker {

	// Do something after worker start
    public static function afterStart(swoole_server $server, int $workerID){
		if($workerID == 0){
			$server->tick(1000, function(){
				Server::stat();
			});
		}
	}

	// Do something after worker stop
	public static function afterStop(swoole_server $server, int $workerID){

	}

	// Do something after websocket open
    public static function afterOpen(swoole_websocket_server $server, swoole_http_request $request){
        
	}
	
	// Do something before websocket message
	public static function beforeMessage(swoole_websocket_server $server, swoole_websocket_frame $frame){
		Response::setInstance($server);
		Request::setFd($frame->fd);
		$data = json_decode($frame->data, TRUE);
		Request::setData($data);
	}

	// Do something after websocket message
	public static function afterMessage(swoole_websocket_server $server, swoole_websocket_frame $frame){
		
	}
	
}