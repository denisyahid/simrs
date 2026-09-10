<?php

namespace App\Console;

use App\Console\Commands\AkomodasiCron;
use App\Console\Commands\MKKOCron;
use App\Console\Commands\SaldoAwalHarianCron;
use App\Console\Commands\BPJSKlaimCron;
use App\Console\Commands\MKKO_read_operasi_jmlpegCron;
use App\Console\Commands\PostSaldoProdukDetail;
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
        //OPEN COMMAND IF NEEDED
        // AkomodasiCron::class,
        // SaldoAwalHarianCron::class,
        // MKKOCron::class,
        // BPJSKlaimCron::class,
        // MKKO_read_operasi_jmlpegCron::class
        PostSaldoProdukDetail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         CARA RUNNING DILOKAL
         # php artisan schedule:work

         LIST SCHEDULER
         # php artisan schedule:list
        */

        // $schedule->job(new \App\Jobs\PostSaldoProdukDetail)->dailyAt('19:35');
        date_default_timezone_set('Asia/Jakarta');
        // $schedule->command('stockotomatis:cron')->dailyAt('00:00');
        // Stock otomatis set waktu di crontab // info crontab -e ( use root )
        $schedule->command('stockotomatis:cron');

        // BUKA COMMAND KALO DIPAKE
        // $schedule->command('akomodasi:cron')->dailyAt('23:45');
        // $schedule->command('BOR:cron')->cron('* * * * *');
        // // $schedule->command('MKKO_read_operasi_jmlpeg:cron')->cron('* * * * *');
        // $schedule->command('BPJSklaim:cron')->cron('* * * * *');
        // // $schedule->command('MKKO:cron')->cron('* * * * *')
        // $schedule->command('saldoawalharian:cron') // ->dailyAt('23:00')
        // ->cron('* * * * *')
        // // ->everyMinute()
        // ->timezone(config('app.timezone'))
        // ->after(function () {
        //     $fp = fopen(storage_path('logs/scheduler.log'), 'a');//opens file in append mode
        //     fwrite($fp,PHP_EOL. "SCHEDULER LARAVEL : ". date('Y-m-d H:i:s') );
        //     fclose($fp);
        // });
        // $schedule->command('inspire')->hourly();
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
