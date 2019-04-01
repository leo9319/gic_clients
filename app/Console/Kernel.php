<?php

namespace App\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //registering my commands
        'App\Console\Commands\sendEmailAndSmsReminder'


    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('sendEmailAndSmsReminder:sendemailsms')
        //           ->everyMinute();// $schedule->command('sendEmailAndSmsReminder:sendemailsms')
        //           ->everyMinute();

        $schedule->command('backup:run')->everyMinute();

        // $schedule->call(function () {
        //     DB::table('fields')->insert([
        //         'field_type' => 'test'
        //     ]);
        // })->everyMinute();

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
