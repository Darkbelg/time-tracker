<?php

namespace App\Console\Commands;

use App\Models\TimeEntryApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Time extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get all()
        $result = TimeEntryApi::get();


        //get first()

        // $result = TimeEntryApi::first();
        // $this->info("id {$result->id}");

        // $result = TimeEntryApi::where('comment', 'Natus')->get();
        dd('result', $result);
    }
}
