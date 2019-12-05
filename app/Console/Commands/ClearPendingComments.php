<?php

namespace App\Console\Commands;

use App\Article;
use App\Comment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ClearPendingComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending-comments:clear';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deny pending comments older than 7 day.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // Log::info('Pending comments working cron job.');
        $sevend_days_ago_date = Carbon::now()->subWeek()->toDateTimeString();


        Comment::where('meta_status','pending')
        ->where('created_at','>=', $sevend_days_ago_date)
        ->update(['meta_status'=>'denied']);
        
        
        
        
        activity()->log('Pending comments older than '.$sevend_days_ago_date.' days denied succesfuly');

        $this->info('Pending comments older than '.$sevend_days_ago_date.' days denied succesfuly.');
    }
}
