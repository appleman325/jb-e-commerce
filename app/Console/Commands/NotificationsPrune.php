<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class NotificationsPrune extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune read notifications older than certain days';

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
        Notification::whereNotNull('read_at')
            ->where('read_at', '<', Carbon::now()->subDays(env('NOTIFICATION_PRUNE_DAY', 7))->format('Y-m-d H:i:s'))
            ->forceDelete();
    }
}
