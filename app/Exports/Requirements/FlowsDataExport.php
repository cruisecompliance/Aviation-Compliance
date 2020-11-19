<?php

namespace App\Exports\Requirements;

use App\Models\Flow;
use App\Models\FlowsData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class FlowsDataExport implements FromCollection, WithHeadings, WithColumnWidths, WithMapping, WithStyles
{
    private $flow;

    public function __construct(Flow $flow)
    {
        $this->flow = $flow;
    }

    /**
     * Adding a heading row
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Sec #',
            'European rule IR/AMC/GM',
            'Rule Reference',
            'Status',
            'Task Owner',
            'Rule Title',
            'AMC3 ORO.MLR.100 Manual Reference',
            'AMC3 ORO.MLR.100 Chapter',
            'Company Manual',
            'Company Chapter',
            'Frequency',
            'Month Quarter',
            'Comments / Questions',
            'Finding / Observation',
            'Deviation Statement',
            'Manual / Evidence Reference',
            'Deviation-Level',
            'Safety level before action',
            'Due-Date',
            'Repetitive Finding ref Number',
            'Correction(s)',
            'Rootcause',
            'Corrective Action(s) Plan',
            'Preventive Action(s)',
            'Action implemented evidence',
            'Safety level after action',
            'Effectiveness Review date',
            'Response date',
            'Extension Due-Date',
            'Closed date',
        ];
    }

    /**
     * Column widths (px)
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5, // Sec #
            'B' => 10, // European rule IR/AMC/GM
            'C' => 20, // Rule Reference
            'D' => 10, // Status
            'E' => 20, // Task Owner
            'F' => 30, // Rule Title
            'G' => 25, // AMC3 ORO.MLR.100 Manual Reference
            'H' => 25, // AMC3 ORO.MLR.100 Chapter
            'I' => 20, // Company Manual
            'J' => 20, // Company Chapter
            'K' => 15, // Frequency
            'L' => 16, // Month Quarter
            'M' => 35, // Comments / Questions
            'N' => 20, // Finding / Observation
            'O' => 20, // Deviation Statement
            'P' => 20, // Manual / Evidence Reference
            'Q' => 20, // Deviation-Level
            'R' => 20, // Safety level before action
            'S' => 15, // Due-Date
            'T' => 20, // Repetitive Finding ref Number
            'U' => 20, // Correction(s)
            'V' => 35, // Rootcause
            'W' => 20, // Corrective Action(s) Plan
            'X' => 20, // Preventive Action(s)
            'Y' => 20, // Action implemented evidence
            'Z' => 20, // Safety level after action
            'AA' => 20, // Effectiveness Review date
            'AB' => 15, // Response date
            'AC' => 15, // Extension Due-Date
            'AD' => 20, // Closed date
        ];
    }

    /**
     * Styling heading row
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 13]],

//            // Styling a specific cell by coordinate.
//            'B2' => ['font' => ['italic' => true]],
//
//            // Styling an entire column.
//            'C'  => ['font' => ['size' => 16]],
        ];
    }

    /**
     * Mapping rows
     *
     * @param $flowsData
     * @return array
     */
    public function map($flowsData): array
    {
        return [
            $flowsData->rule_section,
            $flowsData->rule_group,
            $flowsData->rule_reference,
            $flowsData->task_status,
            $flowsData->owner->name ?? '',
            $flowsData->rule_title,
            $flowsData->rule_manual_reference,
            $flowsData->rule_chapter,
            $flowsData->company_manual,
            $flowsData->company_chapter,
            $flowsData->frequency,
            $flowsData->month_quarter,
            $flowsData->questions,
            $flowsData->finding,
            $flowsData->deviation_statement,
            $flowsData->evidence_reference ? url("storage/auditor/$flowsData->evidence_reference") : '', // file
            $flowsData->deviation_level,
            $flowsData->safety_level_before_action,
            $flowsData->due_date,
            $flowsData->repetitive_finding_ref_number,
            $flowsData->corrections,
            $flowsData->rootcause,
            $flowsData->corrective_actions_plan,
            $flowsData->preventive_actions,
            $flowsData->action_implemented_evidence ? url("storage/investigator/$flowsData->action_implemented_evidence") : '', // file
            $flowsData->safety_level_after_action,
            $flowsData->effectiveness_review_date,
            $flowsData->response_date,
            $flowsData->extension_due_date,
            $flowsData->closed_date,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return FlowsData::whereFlowId($this->flow->id)
            ->with('owner')
            ->get();
    }
}
