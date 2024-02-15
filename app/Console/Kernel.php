<?php

namespace App\Console;

use App\Http\Controllers\EhsanController;
use App\Http\Services\V1\Expert\DutyService;
use App\Http\Services\V1\User\AcceptanceService;
use App\Http\Services\V1\User\ResumeService;
use App\Http\Services\V1\User\TelSupportService;
use App\Jobs\SendEmailWebinarJobTest;
use App\Models\Acceptance;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            (new AcceptanceService)->removeAcceptanceAfter24H();
            (new ResumeService)->removeResumeAfter12H();
            (new TelSupportService)->notify24hRemains();
            (new TelSupportService)->TelSupportTimer();
            (new TelSupportService)->deleteExpiredUnreservedTimes();
            (new DutyService)->notyDoneDuty();
        })->everyFiveMinutes()->name("crons")->withoutOverlapping()->onOneServer();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
