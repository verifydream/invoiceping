<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('invoiceping:remind')->dailyAt('09:00');
