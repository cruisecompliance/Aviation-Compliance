<?php

namespace App\Services\Flows;

use App\Models\Flow;
use App\Models\FlowsData;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Flows\EditTaskMailNotification;
use App\Notifications\Flows\EditTaskTeamsNotification;

class NotificationService
{

    /**
     * @param Flow $flow
     * @param FlowsData $task
     */
    public function sendEmailNotification(Flow $flow, FlowsData $task): void
    {
        // get notification users
        $notificationUsers =  $this->getNotificationUsers($task, $flow->company->id);

        // send notification to email
        Notification::send($notificationUsers, new EditTaskMailNotification($task->rule_reference));

    }


    /**
     * @param Flow $flow
     * @param FlowsData $task
     */
    public function sendTeamsNotification(Flow $flow, FlowsData $task): void
    {

//        $mentionUsers[] = ($task->auditor->azure_name) ? "@" . $task->auditor->azure_name : NULL;
//        $mentionUsers[] = ($task->auditee->name) ? "@" . $task->auditee->name : NULL;
//        $mentionUsers[] = ($task->investigator->name) ? "@" . $task->investigator->name : NULL;

        Notification::send(Auth::user(), new EditTaskTeamsNotification($task->rule_reference));

    }

    /**
     * @param FlowsData $task
     * @param $company_id
     * @return object
     */
    private function getNotificationUsers(FlowsData $task, $company_id ): object
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
