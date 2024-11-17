<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('download:articles')->dailyAt('01:00');
