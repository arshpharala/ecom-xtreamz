<?php

use App\Console\Commands\FetchJasaniData;
use App\Console\Commands\SaveJasaniData;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(new FetchJasaniData())->dailyAt('06:00')->withoutOverlapping();
Schedule::command(new SaveJasaniData())->dailyAt('07:00')->withoutOverlapping();
