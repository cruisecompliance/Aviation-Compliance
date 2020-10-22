<?php


namespace App\Imports\Requirements;


use Illuminate\Support\Collection;

class RequirementsValidation
{
//    /**
//     * Validate requirements import data
//     *
//     * @param array $rows
//     * @return object
//     */
//    public function validate(array $rows): object
//    {
//        $rows = $this->prepare($rows);
//        $empty_requirements = $this->empty($rows);
//        $duplicate_requirements = $this->duplicate($rows);
//
//        return collect([
//            'rows' => $rows,
//            'empty' => $empty_requirements,
//            'duplicate' => $duplicate_requirements,
//        ]);
//    }

    /**
     * Prepare import data
     *
     * @param array $rows
     * @return object
     */
    public function prepare(array $rows): object
    {
        $row = 2; // + 2 header row
        $requirements = [];

        foreach ($rows as $item) {
            $row++;
            if (!empty($item[0]) && !empty($item[1])) {

                $requirements[] = [
                    'row' => $row,
                    'rule_section' => $item[0],
                    'rule_group' => $item[1],
                    'rule_reference' => $item[2],
                    'rule_title' => $item[3],
                    'rule_manual_reference' => $item[4],
                    'rule_chapter' => $item[5],
                ];

            }
        }

        return collect($requirements);
    }


    /**
     * Get empty rule_reference rows
     *
     * @param object $rows
     * @return object
     */
    public function empty(object $rows): object
    {
        $empty_requirements = [];

        foreach ($rows as $item) {

            if (empty($item['rule_reference'])) {
                $empty_requirements[] = [
                    'row' => $item['row'],
                    'rule_section' => $item['rule_section'],
                    'rule_group' => $item['rule_group'],
                    'rule_reference' => $item['rule_reference'],
                    'rule_title' => $item['rule_title'],
                    'rule_manual_reference' => $item['rule_manual_reference'],
                    'rule_chapter' => $item['rule_chapter'],
                ];
            }

        }
        return collect($empty_requirements);
    }

    /**
     * Get rule reference duplicate
     *
     * @param object $rows
     * @return object
     */
    public function duplicate(object $rows): object
    {
        $duplicate_requirements = collect($rows)
            ->duplicates('rule_reference')
            ->filter();

        return collect($duplicate_requirements);
    }
}
