<?php

namespace App\Services\Flows;

use App\Enums\RoleName;
use App\Models\Comment;
use App\Models\Flow;
use App\Models\FlowsData;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Flows\EditTaskMailNotification;
use App\Notifications\Flows\EditTaskTeamsNotification;
use App\Notifications\Flows\TaskReminderDueDateEmailNotification;
use App\Notifications\Flows\TaskReminderMonthEmailNotification;
use App\Notifications\Flows\AddTaskCommentMailNotification;




class NotificationService
{

    // https://teams.microsoft.com/l/entity/<appId>/<entityId>?webUrl=<entityWebUrl>&label=<entityLabel>&context=<context>

    // https://teams.microsoft.com/l/entity/<321b4548-03f7-43d2-b123-ddf6af5f43a1>/<tabID>?webUrl=<https://compliance.maketry.xyz/admin/flows/1/kanban#ORO.GEN.005>&label=<flows/1>&context=<context>
    // appid = 321b4548-03f7-43d2-b123-ddf6af5f43a1
    // entityId = tabID

    /**
     * Send Email Notification  - Task Reminder Due Date
     *
     */
    public function sendTaskReminderDueDateEmailNotification(): void
    {
        // get tasks (with flow.company) that due_date field date expire in 2 weeks
        $tasks = FlowsData::where('due_date', '<=', Carbon::today()->addWeeks(2))->with('flow.company')->get();

        // send notification
        foreach ($tasks as $task) {

            // get notification users
            $notificationUsers = $this->getNotificationUsers($task, $task->flow->company->id);

            // send email notification
            foreach ($notificationUsers as $user) {
                $user->notify(new TaskReminderDueDateEmailNotification($task->rule_reference, $task->due_date->format('d.m.Y'), $user->name));
            }

        }
    }

    /**
     * Send Email Notification  - Task Reminder Month/Quarter
     *
     */
    public function sendTaskReminderMonthEmailNotification(): void
    {
        // get tasks (with flow.company) that month/quarter field date expire in 2 weeks
        $tasks = FlowsData::where('month_quarter', '<=', Carbon::today()->addWeeks(2))->with('flow.company')->get();

        foreach ($tasks as $task) {

            $notificationUsers = $this->getNotificationUsers($task, $task->flow->company->id);

            foreach ($notificationUsers as $user) {
                $user->notify(new TaskReminderMonthEmailNotification($task->rule_reference, $task->month_quarter->format('d.m.Y'), $user->name));
            }
        }
    }

    /**
     * Send Notification in Email - Edit Task
     *
     * @param Flow $flow
     * @param FlowsData $task
     * @param User $user (who change task)
     */
    public function sendEditTaskMailNotification(Flow $flow, FlowsData $task, User $user): void
    {
        // get notification users
        $notificationUsers = $this->getNotificationUsers($task, $flow->company->id);

        // send notification to email
        Notification::send($notificationUsers, new EditTaskMailNotification($task->rule_reference, $user->name));

    }


    /**
     * Send Notification in MS Teams - Edit Task
     *
     * @param Flow $flow
     * @param FlowsData $task
     * @param User $user
     */
    public function sendEditTaskTeamsNotification(Flow $flow, FlowsData $task, User $user): void
    {

//        $mentionUsers[] = ($task->auditor->azure_name) ? "@" . $task->auditor->azure_name : NULL;
//        $mentionUsers[] = ($task->auditee->name) ? "@" . $task->auditee->name : NULL;
//        $mentionUsers[] = ($task->investigator->name) ? "@" . $task->investigator->name : NULL;

        Notification::send(Auth::user(), new EditTaskTeamsNotification($task->rule_reference, $user->name));

    }


    /**
     * Send Notification in Email - Add New Comment
     *
     * @param Flow $flow
     * @param FlowsData $task
     * @param Comment $comment
     * @param User $user
     */
    public function sendAddTaskCommentNotification(Flow $flow, FlowsData $task, Comment $comment, User $user): void
    {
        // get all active users of company (without role SME)
        $notificationUsers = User::whereCompanyId($flow->company_id)
            ->role([
                RoleName::ACCOUNTABLE_MANAGER,
                RoleName::COMPLIANCE_MONITORING_MANAGER,
                RoleName::AUDITOR,
                RoleName::AUDITEE,
                RoleName::INVESTIGATOR,
            ])
            ->active()
            ->get();

        // send notification to email
        Notification::send($notificationUsers, new AddTaskCommentMailNotification($task->rule_reference, $user->name, $comment->message));
    }

    /**
     * Get company user for EditFormNotification
     *
     * @param FlowsData $task
     * @param $company_id
     * @return object
     */
    private function getNotificationUsers(FlowsData $task, $company_id): object
    {
        // get task owner
        $notificationUsers[] = $task->owner;

        // get AM and CMM active users of company
        $users = User::whereCompanyId($company_id)
            ->role([
                RoleName::ACCOUNTABLE_MANAGER,
                RoleName::COMPLIANCE_MONITORING_MANAGER,
            ])
            ->active()
            ->get();

        // merge users list
        $notificationUsers = collect($notificationUsers)->merge($users)->unique()->filter();

        // return users list
        return $notificationUsers;
    }


}
