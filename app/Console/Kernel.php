<?php

namespace App\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Reminder;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //registering my commands
        'App\Console\Commands\sendEmailAndSmsReminder',
        'App\Console\Commands\PendingClients',


    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $from = Carbon::today()->toDateString();
        $to = Carbon::tomorrow()->toDateString();

        $todays_schedule = Reminder::whereBetween('end_date', [$from, $to])->where('status', 1)->first();

        $schedule->command('pending:clients')->daily()->at('09:00')->when(function () use ($todays_schedule) {
            return $todays_schedule;
        });

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
