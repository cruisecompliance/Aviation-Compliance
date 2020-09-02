<?php

namespace App\Services\Flows;

use App\Models\Flow;
use App\Models\FlowsData;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Flows\EditTaskMailNotification;
use App\Notifications\Flows\EditTaskTeamsNotification;
use App\Notifications\Flows\TaskDeadlineNotification;


class NotificationService
{

    /**
     *
     */
    public function sendTaskDeadlineEmailNotification(): void
    {
        // get tasks(with flow.company) that expire in 2 weeks
        $tasks = FlowsData::where('due_date', '<=', Carbon::today()->addWeek(2))->with('flow.company')->get();

        // send notification
        foreach ($tasks as $task) {

            // get notification users
            $notificationUsers = $this->getNotificationUsers($task, $task->flow->company->id);

            // send email notification
            foreach ($notificationUsers as $user) {
                $user->notify(new TaskDeadlineNotification($task->rule_reference, $task->due_date->format('d.m.Y'), $user->name));
                sleep(2);
            }

        }
    }

    /**
     * @param Flow $flow
     * @param FlowsData $task
     * @param User $user (who change task)
     */
    public function sendEmailNotification(Flow $flow, FlowsData $task, User $user): void
    {
        // get notification users
        $notificationUsers = $this->getNotificationUsers($task, $flow->company->id);

        // send notification to email
        Notification::send($notificationUsers, new EditTaskMailNotification($task->rule_reference, $user->name));

    }


    /**
     * @param Flow $flow
     * @param FlowsData $task
     * @param User $user
     */
    private function sendTeamsNotification(Flow $flow, FlowsData $task, User $user): void
    {

//        $mentionUsers[] = ($task->auditor->azure_name) ? "@" . $task->auditor->azure_name : NULL;
//        $mentionUsers[] = ($task->auditee->name) ? "@" . $task->auditee->name : NULL;
//        $mentionUsers[] = ($task->investigator->name) ? "@" . $task->investigator->name : NULL;

        Notification::send(Auth::user(), new EditTaskTeamsNotification($task->rule_reference, $user->name));

    }

    /**
     * @param FlowsData $task
     * @param $company_id
     * @return object
     */
    private function getNotificationUsers(FlowsData $task, $company_id): object
    {
        $accountableManagers = User::AM()->active()->whereCompanyId($company_id)->get();
        $complianceMonitoringManagers = User::CMM()->active()->whereCompanyId($company_id)->get();

        $notificationUsers[] = $task->auditor;
        $notificationUsers[] = $task->auditee;
        $notificationUsers[] = $task->investigator;

        $notificationUsers = collect($notificationUsers)->merge($accountableManagers)->unique()->filter();
        $notificationUsers = collect($notificationUsers)->merge($accountableManagers)->unique()->filter();
        $notificationUsers = collect($notificationUsers)->merge($complianceMonitoringManagers)->unique()->filter();

        return $notificationUsers;
    }


}
