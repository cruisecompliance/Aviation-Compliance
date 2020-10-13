<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\RoleName;
use App\Enums\PermissionName;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * create permissions
         */
        // European Rule
        Permission::create(['name' => PermissionName::EDIT_RULE_SECTION]);
        Permission::create(['name' => PermissionName::EDIT_RULE_GROUP]);
        Permission::create(['name' => PermissionName::EDIT_RULE_REFERENCE]);
        Permission::create(['name' => PermissionName::EDIT_RULE_TITLE]);
        Permission::create(['name' => PermissionName::EDIT_RULE_MANUAL_REFERENCE]);
        Permission::create(['name' => PermissionName::EDIT_RULE_CHAPTER]);

        // Company Structure
        Permission::create(['name' => PermissionName::EDIT_COMPANY_MANUAL]);
        Permission::create(['name' => PermissionName::EDIT_COMPANY_CHAPTER]);

        // Audit Structure
        Permission::create(['name' => PermissionName::EDIT_FREQUENCY]);
        Permission::create(['name' => PermissionName::EDIT_MONTH_QUARTER]);
        Permission::create(['name' => PermissionName::EDIT_ASSIGNED_AUDITOR]);
        Permission::create(['name' => PermissionName::EDIT_ASSIGNED_AUDITEE]);

        // Auditors Input
        Permission::create(['name' => PermissionName::EDIT_QUESTIONS]);
        Permission::create(['name' => PermissionName::EDIT_FINDING]);
        Permission::create(['name' => PermissionName::EDIT_DEVIATION_STATEMENT]);
        Permission::create(['name' => PermissionName::EDIT_EVIDENCE_REFERENCE]);
        Permission::create(['name' => PermissionName::EDIT_DEVIATION_LEVEL]);
        Permission::create(['name' => PermissionName::EDIT_SAFETY_LEVEL_BEFORE_ACTION]);
        Permission::create(['name' => PermissionName::EDIT_DUE_DATE]);
        Permission::create(['name' => PermissionName::EDIT_REPETITIVE_FINDING_REF_NUMBER]);

        // Auditee Input (NP)
        Permission::create(['name' => PermissionName::EDIT_ASSIGNED_INVESTIGATOR]);
        Permission::create(['name' => PermissionName::EDIT_CORRECTIONS]);
        Permission::create(['name' => PermissionName::EDIT_ROOTCASE]);
        Permission::create(['name' => PermissionName::EDIT_CORRECTIVE_ACTIONS_PLAN]);
        Permission::create(['name' => PermissionName::EDIT_PREVENTIVE_ACTIONS]);
        Permission::create(['name' => PermissionName::EDIT_ACTION_IMPLEMENTED_EVIDENCE]);
        Permission::create(['name' => PermissionName::EDIT_SAFETY_LEVEL_AFTER_ACTION]);
        Permission::create(['name' => PermissionName::EDIT_EFFECTIVENESS_REVIEW_DATE]);
        Permission::create(['name' => PermissionName::EDIT_RESPONSE_DATE]);
        Permission::create(['name' => PermissionName::EDIT_EXTENSION_DUE_DATE]);
        Permission::create(['name' => PermissionName::EDIT_CLOSED_DATE]);


        /**
         * create role
         */
        // SME
        Role::create(['name' => RoleName::SME])->givePermissionTo([
            PermissionName::EDIT_RULE_SECTION,
            PermissionName::EDIT_RULE_GROUP,
            PermissionName::EDIT_RULE_REFERENCE,
            PermissionName::EDIT_RULE_TITLE,
            PermissionName::EDIT_RULE_MANUAL_REFERENCE,
            PermissionName::EDIT_RULE_CHAPTER,

            PermissionName::EDIT_RESPONSE_DATE,

            // AM
            PermissionName::EDIT_EXTENSION_DUE_DATE,

            // CMM
            PermissionName::EDIT_COMPANY_MANUAL,
            PermissionName::EDIT_COMPANY_CHAPTER,
            PermissionName::EDIT_FREQUENCY,
            PermissionName::EDIT_MONTH_QUARTER,
            PermissionName::EDIT_ASSIGNED_AUDITOR,
            PermissionName::EDIT_ASSIGNED_AUDITEE,
            PermissionName::EDIT_DUE_DATE,

            // Auditor
            PermissionName::EDIT_QUESTIONS,
            PermissionName::EDIT_FINDING,
            PermissionName::EDIT_DEVIATION_STATEMENT,
            PermissionName::EDIT_EVIDENCE_REFERENCE,
            PermissionName::EDIT_DEVIATION_LEVEL,
            PermissionName::EDIT_SAFETY_LEVEL_BEFORE_ACTION,
            PermissionName::EDIT_DUE_DATE,
            PermissionName::EDIT_REPETITIVE_FINDING_REF_NUMBER,

            // Auditee
            PermissionName::EDIT_ASSIGNED_INVESTIGATOR,
            PermissionName::EDIT_CORRECTIONS,
            PermissionName::EDIT_ROOTCASE,
            PermissionName::EDIT_CORRECTIVE_ACTIONS_PLAN,
            PermissionName::EDIT_PREVENTIVE_ACTIONS,
            PermissionName::EDIT_ACTION_IMPLEMENTED_EVIDENCE,
            PermissionName::EDIT_SAFETY_LEVEL_AFTER_ACTION,
            PermissionName::EDIT_EFFECTIVENESS_REVIEW_DATE,

            // Investigator
            PermissionName::EDIT_SAFETY_LEVEL_BEFORE_ACTION,
            PermissionName::EDIT_DUE_DATE,
            PermissionName::EDIT_REPETITIVE_FINDING_REF_NUMBER,
            PermissionName::EDIT_ASSIGNED_INVESTIGATOR,
            PermissionName::EDIT_CORRECTIONS,
            PermissionName::EDIT_ROOTCASE,
            PermissionName::EDIT_CORRECTIVE_ACTIONS_PLAN,
            PermissionName::EDIT_PREVENTIVE_ACTIONS,
            PermissionName::EDIT_ACTION_IMPLEMENTED_EVIDENCE,

        ]);

        // Accountable Manager(AM)
        Role::create(['name' => RoleName::ACCOUNTABLE_MANAGER])->givePermissionTo([
            PermissionName::EDIT_EXTENSION_DUE_DATE,
        ]);

        // Compliance Monitoring Manager(CMM)
        Role::create(['name' => RoleName::COMPLIANCE_MONITORING_MANAGER])->givePermissionTo([
            PermissionName::EDIT_COMPANY_MANUAL,
            PermissionName::EDIT_COMPANY_CHAPTER,
            PermissionName::EDIT_FREQUENCY,
            PermissionName::EDIT_MONTH_QUARTER,
            PermissionName::EDIT_ASSIGNED_AUDITOR,
            PermissionName::EDIT_ASSIGNED_AUDITEE,
            PermissionName::EDIT_DUE_DATE,
        ]);

        // Auditor
        Role::create(['name' => RoleName::AUDITOR])->givePermissionTo([
            PermissionName::EDIT_QUESTIONS,
            PermissionName::EDIT_FINDING,
            PermissionName::EDIT_DEVIATION_STATEMENT,
            PermissionName::EDIT_EVIDENCE_REFERENCE,
            PermissionName::EDIT_DEVIATION_LEVEL,
            PermissionName::EDIT_SAFETY_LEVEL_BEFORE_ACTION,
            PermissionName::EDIT_DUE_DATE,
            PermissionName::EDIT_REPETITIVE_FINDING_REF_NUMBER,
        ]);

        // Auditee(Nominated Person, NP)
        Role::create(['name' => RoleName::AUDITEE])->givePermissionTo([
            PermissionName::EDIT_ASSIGNED_INVESTIGATOR,
            PermissionName::EDIT_CORRECTIONS,
            PermissionName::EDIT_ROOTCASE,
            PermissionName::EDIT_CORRECTIVE_ACTIONS_PLAN,
            PermissionName::EDIT_PREVENTIVE_ACTIONS,
            PermissionName::EDIT_ACTION_IMPLEMENTED_EVIDENCE,
            PermissionName::EDIT_SAFETY_LEVEL_AFTER_ACTION,
            PermissionName::EDIT_EFFECTIVENESS_REVIEW_DATE,
        ]);

        // Investigator
        Role::create(['name' => RoleName::INVESTIGATOR])->givePermissionTo([
            PermissionName::EDIT_SAFETY_LEVEL_BEFORE_ACTION,
            PermissionName::EDIT_DUE_DATE,
            PermissionName::EDIT_REPETITIVE_FINDING_REF_NUMBER,
            PermissionName::EDIT_ASSIGNED_INVESTIGATOR,
            PermissionName::EDIT_CORRECTIONS,
            PermissionName::EDIT_ROOTCASE,
            PermissionName::EDIT_CORRECTIVE_ACTIONS_PLAN,
            PermissionName::EDIT_PREVENTIVE_ACTIONS,
            PermissionName::EDIT_ACTION_IMPLEMENTED_EVIDENCE,
        ]);


    }
}
