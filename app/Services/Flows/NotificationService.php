<?php

namespace App\Services\Flows;

use App\Enums\RoleName;
use App\Models\Comment;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Notifications\Flows\TaskOwnerNotification;
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

            // get all active users of company (without role SME)
            $notificationUsers = $this->getNotificationUsers($task, $task->flow->company->id);

            // send notification to email
            foreach ($notificationUsers as $user) {

                // generate URL for user (link or deeplink for MS Teams)
                $link = $this->generateTaskUrl($task->rule_reference, $user);

                // send notification to user mail
                $user->notify(new TaskReminderDueDateEmailNotification($task->rule_reference, $task->due_date, $user->name, $link));
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

            // get all active users of company (without role SME)
            $notificationUsers = $this->getNotificationUsers($task, $task->flow->company->id);

            // send notification to email
            foreach ($notificationUsers as $user) {

                // generate URL for user (link or deeplink for MS Teams)
                $link = $this->generateTaskUrl($task->rule_reference, $user);

                // send notification to user mail
                $user->notify(new TaskReminderMonthEmailNotification($task->rule_reference, $task->month_quarter, $user->name, $link));
            }
        }
    }

    /**
     * Send Notification in Email - Edit Task
     *
     * @param Flow $flow
     * @param FlowsData $task
     * @param User $editor (who change task)
     */
    public function sendEditTaskMailNotification(Flow $flow, FlowsData $task, User $editor): void
    {
        // get all active users of company (without role SME)
        $notificationUsers = $this->getNotificationUsers($task, $flow->company->id);

        // send notification to email
        foreach ($notificationUsers as $user) {

            // generate URL for user (link or deeplink for MS Teams)
            $link = $this->generateTaskUrl($task->rule_reference, $user);

            // send notification to user mail
            $user->notify(new EditTaskMailNotification($task->rule_reference, $editor->name, $link));
        }
    }


//    /**
//     * Send Notification in MS Teams - Edit Task
//     *
//     * @param Flow $flow
//     * @param FlowsData $task
//     * @param User $user
//     */
//    public function sendEditTaskTeamsNotification(Flow $flow, FlowsData $task, User $user): void
//    {
//
////        $mentionUsers[] = ($task->auditor->azure_name) ? "@" . $task->auditor->azure_name : NULL;
////        $mentionUsers[] = ($task->auditee->name) ? "@" . $task->auditee->name : NULL;
////        $mentionUsers[] = ($task->investigator->name) ? "@" . $task->investigator->name : NULL;
//
//        Notification::send(Auth::user(), new EditTaskTeamsNotification($task->rule_reference, $user->name));
//
//    }

    /**
     * Send Notification in Email - Changed Task Owner
     *
     * @param Flow $flow
     * @param FlowsData $task
     * @param User $editor
     */
    public function sendTaskOwnerNotification(Flow $flow, FlowsData $task, User $editor): void
    {
        // get all active users of company (without role SME)
        $notificationUsers = $this->getNotificationUsers($task, $flow->company->id);

        // send notification to email
        foreach ($notificationUsers as $user) {

            // generate URL for user (link or deeplink for MS Teams)
            $link = $this->generateTaskUrl($task->rule_reference, $user);

            // send notification to user mail
            $user->notify(new TaskOwnerNotification($task->rule_reference, $task->owner->name, $editor->name, $link));
        }

    }

    /**
     * Send Notification in Email - Add New Comment
     *
     * @param Flow $flow
     * @param FlowsData $task
     * @param Comment $comment
     * @param User $comment_author
     */
    public function sendAddTaskCommentNotification(Flow $flow, FlowsData $task, Comment $comment, User $comment_author): void
    {
        // get all active users of company (without role SME)
        $notificationUsers = $this->getNotificationUsers($task, $flow->company->id);

        // send notification to email
        foreach ($notificationUsers as $user) {

            // generate URL for user (link or deeplink for MS Teams)
            $link = $this->generateTaskUrl($task->rule_reference, $user);

            // send notification to user mail
            $user->notify(new AddTaskCommentMailNotification($task->rule_reference, $comment_author->name, $comment->message, $link));
        }
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
        // get all active users of company (without role SME)
        $notificationUsers = User::whereCompanyId($company_id)
            ->role([
                RoleName::ACCOUNTABLE_MANAGER,
                RoleName::COMPLIANCE_MONITORING_MANAGER,
                RoleName::AUDITOR,
                RoleName::AUDITEE,
                RoleName::INVESTIGATOR,
            ])
            ->active()
            ->get();

        // return users list
        return $notificationUsers;
    }


    /**
     * Generating link for email notification (link or deeplink)
     *
     * https://docs.microsoft.com/en-us/microsoftteams/platform/concepts/build-and-test/deep-links#generating-a-deep-link-to-your-tab
     *
     * @param string $rule_reference
     * @param User $user
     * @return string
     */
    private function generateTaskUrl(string $rule_reference, User $user): string
    {
        if(!empty($user->azure_name)) {
            // generate deeplink for MS Teams
            $encodedWebUrl = urlencode(url('/user/flows/table'));
            // $encodedWebUrl = urlencode('https://compliance.maketry.xyz/user/flows/table'); // for stage test
            $encodedContext = urlencode('{"subEntityId": "' . $rule_reference . '"}');
            $taskUrl = 'https://teams.microsoft.com/l/entity/' . env('MS_TEAMS_APP_ID') . '/tabID?webUrl=' . $encodedWebUrl . '&context=' .$encodedContext;

            return $taskUrl;

        } else {
            // generate link for web page
            $encodedRuleReference = rawurlencode($rule_reference);
            $taskUrl = url("/user/flows/table#$encodedRuleReference");

            return $taskUrl;
        }

    }


}
