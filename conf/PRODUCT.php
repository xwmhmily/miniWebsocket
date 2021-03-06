<?php

$config = [
	'common' => [
		'app_name'                 => 'Mini_Websocket',
		'app_version'              => '2.0',
		'tb_pk'                    => 'id',
		'tb_prefix'                => 'sl_',
		'tb_suffix_sf'             => '_',
		'user'                     => 'www',
		'group'                    => 'www',
		'backlog'                  => 128,
		'daemonize'                => 1,
		'worker_num'               => 4,
		'task_ipc_mode'            => 1,
		'task_worker_num'          => 1,
		'open_cpu_affinity'        => 1,
		'dispatch_mode'            => 2,
		'heartbeat_idle_time'      => 120,
		'heartbeat_check_interval' => 30,
		'open_eof_check'           => TRUE,
		'package_eof'              => "\r\n",
		'package_length_type'      => 'N',
		'package_length_offset'    => 8,
  		'package_body_offset'      => 16,
		'log_level'                => 4,
		'error_level'              => 2,
		'module'                   => 'api',
		'log_method'               => 'redis',
		'pid_file'                 => __DIR__.'/../log/swoole.pid',
		'stat_file'                => __DIR__.'/../log/swoole.stat',
		'log_file'       => __DIR__.'/../log/swoole_error_'.date('Y-m-d').'.log',
		'mysql_log_file' => __DIR__.'/../log/swoole_mysql_'.date('Y-m-d').'.log',
	],

	'websocket' => [
		'enable' => TRUE,
		'ip'     => '*',
		'port'   => 9509,
	],

	'mysql' => [
		'db'   => 'test',
		'host' => '127.0.0.1',
		'port' => 3306,
		'user' => 'root',
		'pwd'  => '123456789',
		'max'  => 3,
		'log_sql' => true,
	],
	
	'redis' => [
		'db'   => '0',
		'host' => '127.0.0.1',
		'port' => 6379,
		'pwd'  => '123456789',
	],

	'redis_log' => [
		'db'    => '0',
		'host'  => '127.0.0.1',
		'port'  => 6379,
		'pwd'   => '123456789',
		'queue' => 'Queue_mini_log',
	],

	'process' => [
		'Mini_Swoole_importer'=> [
			'num' => 1, 
			'mysql' => true,
			'redis' => true,
			'callback' => ['Importer', 'run'],
		],
	],
];

return $config;