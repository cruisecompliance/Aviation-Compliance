<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CMM_Backlog()
 */
final class RequrementStatus extends Enum
{

    const CMM_Backlog = 'CMM Backlog';
    const Auditor_Review = 'Auditor Review';
    const Auditee_Review = 'Auditee Review';
    const Investigator_Review = 'Investigator Review';
    const CMM_Extension_Review = 'CMM Extension Review';
    const AM_Extension_Review = 'AM Extension Review';
    const CMM_Review = 'CMM Review';
    const CMM_Done = 'CMM Done';


    /**
     * Status transitions
     * Backlog -> Auditor Review (CMM)
     * Auditor Review -> Auditee  Review, CMM Review
     * Auditee  Review -> CMM Extension Review, Investigator Review, Auditor Review
     * Investigator Review -> Auditor Review
     * CMM Extension Review -> AM Extension Review, Auditee  Review
     * AM Extension Review -> Auditee  Review
     * CMM Review -> Auditor Review, Done
     *
     * @return \Illuminate\Support\Collection
     */
    public static function statusTransitions(): object
    {
        return collect([
            [
                'status_name' => self::CMM_Backlog,
                'status_transitions' => [
                    self::Auditor_Review,
                ]
            ], [
                'status_name' => self::Auditor_Review,
                'status_transitions' => [
                    self::Auditee_Review,
                    self::CMM_Review,
                ]
            ], [
                'status_name' => self::Auditee_Review,
                'status_transitions' => [
                    self::CMM_Extension_Review,
                    self::Investigator_Review,
                    self::Auditor_Review,
                ]
            ], [
                'status_name' => self::Investigator_Review,
                'status_transitions' => [
                    self::Auditor_Review,
                ]
            ], [
                'status_name' => self::CMM_Extension_Review,
                'status_transitions' => [
                    self::AM_Extension_Review,
                    self::Auditee_Review,
                ]
            ], [
                'status_name' => self::AM_Extension_Review,
                'status_transitions' => [
                    self::Auditee_Review,
                ]
            ], [
                'status_name' => self::CMM_Review,
                'status_transitions' => [
                    self::Auditor_Review,
                    self::CMM_Done,
                ]
            ], [
                'status_name' => self::CMM_Done,
                'status_transitions' => [
//                    self::Auditor_Review,
//                    self::CMM_Done,
                ]
            ]
        ]);
    }

//    public static function roleTransitions(): object
//    {
//        return collect([
//            [
//                'role_name' => RoleName::COMPLIANCE_MONITORING_MANAGER,
//                'role_transitions' => [
//                    self::Auditor_Review,
//                    self::AM_Extension_Review,
//                    self::Auditee_Review,
//                    self::CMM_Done,
//                ]
//            ], [
//                'role_name' => RoleName::AUDITOR,
//                'role_transitions' => [
//                    self::Auditee_Review,
//                    self::CMM_Review,
//                ]
//            ], [
//                'role_name' => RoleName::AUDITEE,
//                'role_transitions' => [
//                    self::CMM_Extension_Review,
//                    self::Investigator_Review,
//                    self::Auditor_Review,
//                ]
//            ], [
//                'role_name' => RoleName::INVESTIGATOR,
//                'role_transitions' => [
//                    self::Auditor_Review,
//                ]
//            ], [
//                'role_name' => RoleName::ACCOUNTABLE_MANAGER,
//                'role_transitions' => [
//                    self::Auditee_Review,
//                ]
//            ]
//        ]);
//    }
//
    public static function roleStatuses()
    {
        return collect([
            [
                'role_name' => RoleName::COMPLIANCE_MONITORING_MANAGER,
                'role_statuses' => [
                    self::CMM_Backlog,
                    self::CMM_Extension_Review,
                    self::CMM_Review,
                    self::CMM_Done,
                ]
            ], [
                'role_name' => RoleName::AUDITOR,
                'role_statuses' => [
                    self::Auditor_Review,
                ]
            ], [
                'role_name' => RoleName::AUDITEE,
                'role_statuses' => [
                    self::Auditee_Review,
                ]
            ], [
                'role_name' => RoleName::INVESTIGATOR,
                'role_statuses' => [
                    self::Investigator_Review,
                ]
            ], [
                'role_name' => RoleName::ACCOUNTABLE_MANAGER,
                'role_statuses' => [
                    self::AM_Extension_Review,
                ]
            ]
        ]);
    }


//
//    public static function getRoleTransitions(string $role_name)
//    {
//        return self::roleTransitions()->where('role_name', $role_name)->first()['role_transitions'];
//    }

    public static function getRoleStatuses(string $role_name)
    {
        return self::roleStatuses()->where('role_name', $role_name)->first()['role_statuses'];
    }

    public static function getStatusTransitions(string $status_name)
    {
        return self::statusTransitions()->where('status_name', $status_name)->first()['status_transitions'];
    }
}

