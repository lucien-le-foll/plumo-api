<?php

namespace App\Console;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourlyAt();

        $schedule->call(function(){
            $client = new Client([
                'base_uri' => 'https://api.ionic.io'
            ]);

            try {
                $client->request('POST', '/push/notifications', [
                    'json' => [
                        "tokens" => ["cmn6h4F3SEg:APA91bGkrz6ij8JeUcumThu_DaZrA5coJukx77gT1OUzpFjPmicKqJUC9gxXvJK5tztj68c9r6CBr3oUuhw5Ni0SerhpnMisiPixzwJHG60XTz5rnUklTrCy95VBu0bkzEWNG81w6rJJ"],
                        "profile" => "development",
                        "notification" => [
                            "message" => "Notification envoyée automatiquement"
                        ]
                    ]
                ]);
            } catch (ClientException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                Log::info($responseBodyAsString);
            }
        })->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
