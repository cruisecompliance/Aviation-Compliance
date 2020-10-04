<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static EDIT_RULE_SECTION()

 */
final class PermissionName extends Enum
{
    /**
     * Requirements
     */

    // European Rule
    const EDIT_RULE_SECTION = 'edit_rule_section';
    const EDIT_RULE_GROUP = 'edit_rule_group';
    const EDIT_RULE_REFERENCE = 'edit_rule_reference';
    const EDIT_RULE_TITLE = 'edit_rule_title';
    const EDIT_RULE_MANUAL_REFERENCE = 'edit_rule_manual_reference';
    const EDIT_RULE_CHAPTER = 'edit_rule_chapter';

    // Company Structure
    const EDIT_COMPANY_MANUAL = 'edit_company_manual';
    const EDIT_COMPANY_CHAPTER = 'edit_company_chapter';

    // Audit Structure
    const EDIT_FREQUENCY = 'edit_frequency';
    const EDIT_MONTH_QUARTER = 'edit_month_quarter';
    const EDIT_ASSIGNED_AUDITOR = 'edit_assigned_auditor'; // ROLE
    const EDIT_ASSIGNED_AUDITEE = 'edit_assigned_auditee'; // ROLE

    // Auditors Input
    const EDIT_QUESTIONS = 'edit_questions';
    const EDIT_FINDING = 'edit_finding';
    const EDIT_DEVIATION_STATEMENT = 'edit_deviation_statement';
    const EDIT_EVIDENCE_REFERENCE = 'edit_evidence_reference';
    const EDIT_DEVIATION_LEVEL = 'edit_deviation_level';
    const EDIT_SAFETY_LEVEL_BEFORE_ACTION = 'edit_safety_level_before_action';
    const EDIT_DUE_DATE = 'edit_due_date';
    const EDIT_REPETITIVE_FINDING_REF_NUMBER = 'edit_repetitive_finding_ref_number';

    // Auditee Input (NP)
    const EDIT_ASSIGNED_INVESTIGATOR = 'edit_assigned_investigator'; // ROLE
    const EDIT_CORRECTIONS = 'edit_corrections';
    const EDIT_ROOTCASE = 'edit_rootcause';
    const EDIT_CORRECTIVE_ACTIONS_PLAN = 'edit_corrective_actions_plan';
    const EDIT_PREVENTIVE_ACTIONS = 'edit_preventive_actions';
    const EDIT_ACTION_IMPLEMENTED_EVIDENCE = 'edit_action_implemented_evidence';
    const EDIT_SAFETY_LEVEL_AFTER_ACTION = 'edit_safety_level_after_action';
    const EDIT_EFFECTIVENESS_REVIEW_DATE = 'edit_effectiveness_review_date';
    const EDIT_RESPONSE_DATE = 'edit_response_date';

    //
    const EDIT_EXTENSION_DUE_DATE = 'edit_extension_due_date';
    const EDIT_CLOSED_DATE = 'edit_closed_date';

}
