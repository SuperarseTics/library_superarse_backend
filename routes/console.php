<?php

use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:clear-bookings')->daily();
Schedule::command('app:delayed-give-back')->daily();
