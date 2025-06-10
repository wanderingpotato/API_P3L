<?php

use App\Console\Commands\CheckBatasWaktuBayar;
use App\Console\Commands\CheckOverBatasWaktuAmbilPembeli;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::command('app:check-batas-waktu-bayar')->everyMinute();
Schedule::command('app:check-over-batas-waktu-ambil-pembeli')->everyMinute();
Schedule::command('app:check-top-seller')->monthlyOn(1, '00:00');
Schedule::command('app:check7-days')->daily();