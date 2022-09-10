<?php


namespace Lite\Cron;


class CronHandler
{
	/**
	 * @var array
	 */
	private static $jobs = [];

	public static function register($job){
		self::$jobs[] = $job;
	}

	public static function run(){
		foreach (self::$jobs as $job)
			call_user_func_array([new $job(), 'run'],[]);
	}
}