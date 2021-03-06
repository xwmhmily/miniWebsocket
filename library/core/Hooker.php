<?php
/*
 * Server callback functions
 * Remark: 1.7.15+版本, 当设置dispatch_mode = 1/3 时会自动去掉 onConnect / onClose 事件回调
 * */

class Hooker {

    // Manager start
    public static function onManagerStart(swoole_server $server){
        if(strtoupper(PHP_OS) == Server::OS_LINUX){
            swoole_set_process_name(APP_NAME.'_manager');
        }
    }

    // Worker start
    public static function onWorkerStart(swoole_server $server, int $workerID){
		if ($server->taskworker) {
            $max = 1;
            $process_name = APP_NAME.'_task';
        }else{
            $config = Config::get(Pool::TYPE_MYSQL);
            $max = $config['max'];
            !$max && $max = 1;
            $process_name = APP_NAME.'_worker';
        }

        if(strtoupper(PHP_OS) == Server::OS_LINUX){
            swoole_set_process_name($process_name);
        }

        for($i = 1; $i <= $max; $i++){
            $retval = Pool::getInstance(Pool::TYPE_MYSQL);
            if($retval === FALSE){
                Logger::error('Worker '.$workerID.' fail to connect MySQL !');
            }
        }

        $retval = Pool::getInstance(Pool::TYPE_REDIS);
        if($retval === FALSE){
            Logger::error('Worker '.$workerID.' fail to connect Redis !');
        }

        Worker::afterStart($server, $workerID);
        Logger::log('Worker '.$workerID.' ready for connections ...');
    }

    // Websocket onOpen
    public static function onOpen(swoole_websocket_server $server, swoole_http_request $request){
        Worker::afterOpen($server, $request);
    }

    // Websocket onMessage
    public static function onMessage(swoole_websocket_server $server, swoole_websocket_frame $frame){
        Worker::beforeMessage($server, $frame);
        
        $fd = $frame->fd;
        $data = json_decode($frame->data, TRUE);
        if(!$data){
            $rep['code']  = 0;
            $rep['error'] = 'Not valid JSON';
            $server->push($fd, JSON($rep));
        }else{
            $module = isset($data['module']) ? $data['module'] : 'index';
            $controller = $data['controller'];
            $action = $data['action'];

            if($controller){
                $instance = Helper::import($module, $controller);
      
                $middleware_status = Response::getMiddlewareStatus();
                if($middleware_status !== FALSE){
                    if($instance !== FALSE){
                        try{
                            $retval = $instance->$action();
                            $server->push($fd, $retval);
                        }catch(Throwable $e){
                            $server->push($fd, $e->getMessage());
                        }
                    }else{
                        $rep['code']  = 0;
                        $rep['error'] = 'Controller '.$controller.' not found';
                        $server->push($fd, JSON($rep));
                    }
                }else{
                    Response::endByMiddleware();
                }
            }
        }
    }

    // onClose
    public static function onClose(swoole_server $server, int $fd, int $reactorID){
        Worker::afterClose($server, $fd, $reactorID);
    }

    // Worker error
	public static function onWorkerError(swoole_server $serv, int $workerID, int $workerPID, int $exitCode, int $signal){
		Logger::fatal('Worker '.$workerID.' exit with code '.$exitCode.' and signal '.$signal);
	}

    // Worker stop
	public static function onWorkerStop(swoole_server $server, int $workerID){
		Worker::afterStop($server, $workerID);
		Logger::log('Worker '.$workerID.' stop');
	}

}