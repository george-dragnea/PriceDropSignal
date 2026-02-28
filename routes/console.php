<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('prices:check')->dailyAt('07:07');
Schedule::command('prices:check')->dailyAt('16:23');
