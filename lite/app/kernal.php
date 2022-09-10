<?php

use Lite\Cron\CronHandler;

//CronHandler::register(App\Jobs\CoverChecker::class);
CronHandler::register(\App\Jobs\VideoChecker::class);