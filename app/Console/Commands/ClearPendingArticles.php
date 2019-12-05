<?php

namespace App\Console\Commands;

use App\Article;
use App\Comment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ClearPendingArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending-articles:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deny pending articles older than 7 day.';

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
        $sevend_days_ago_date = Carbon::now()->subWeek()->toDateTimeString();


        Article::where('meta_status', 'pending')
            ->where('created_at', '>=', $sevend_days_ago_date)
            ->update(['meta_status' => 'denied']);

        activity()->log('Pending articles older than '.$sevend_days_ago_date.' days denied succesfuly');
        $this->info('Pending articles older than '.$sevend_days_ago_date.' days denied succesfuly.');
    }
}
