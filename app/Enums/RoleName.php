<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SME()
 * @method static static ACCOUNTABLE_MANAGER()
 */
final class RoleName extends Enum
{
    const SME = 'Subject Matter Expert (SME)'; // Subject Matter Expert (SME) - System Administrator
    const ACCOUNTABLE_MANAGER = 'Accountable Manager (AM)'; // Accountable Manager (AM)
    const COMPLIANCE_MONITORING_MANAGER = 'Compliance Monitoring Manager (CMM)'; // Compliance Monitoring Manager (CMM)
    const AUDITOR = 'Auditor'; // Auditor
    const AUDITEE = 'Auditee (Nominated Person, NP)'; // Auditee (Nominated Person, NP)
    const INVESTIGATOR = 'Investigator'; // Investigator

}
