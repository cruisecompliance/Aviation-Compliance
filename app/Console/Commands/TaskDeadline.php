<?php

namespace App\Console\Commands;

use App\Services\Flows\NotificationService;
use Illuminate\Console\Command;

class TaskDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifications when due-date approaches (2 weeks left)';

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
     * @return int
     */
    public function handle()
    {
        // \Log::info("Cron is working fine!");

        // send email
        app(NotificationService::class)->sendTaskDeadlineEmailNotification();

        $this->info('Successfully sent daily task reminder to everyone.');
    }
}
