<?php

namespace App\Console\Commands;

use App\Services\Flows\NotificationService;
use Illuminate\Console\Command;

class TaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifications when due-date and month/quarter approaches (2 weeks left)';

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

        // send Task Reminder (due date)
        app(NotificationService::class)->sendTaskReminderDueDateEmailNotification();

        // send Task Reminder (month/quarter)
        app(NotificationService::class)->sendTaskReminderMonthEmailNotification();

        $this->info('Successfully sent daily task reminder to everyone.');
    }
}
