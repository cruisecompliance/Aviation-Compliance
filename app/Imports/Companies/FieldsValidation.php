<?php


namespace App\Imports\Companies;


use Illuminate\Support\Collection;

class FieldsValidation
{
    /**
     * Prepare import data
     *
     * @param array $rows
     * @return object
     */
    public function prepare(array $rows): object
    {
        $row = 1; // + 1 header row
        $fields = [];

        foreach ($rows as $item) {
            $row++;
            if (!empty($item[0])) {

                $fields[] = [
                    'row' => $row,
                    'rule_reference' => $item[0],
                    'company_manual' => $item[1],
                    'company_chapter' => $item[2],
                    ];

            }
        }

        return collect($fields);
    }

    /**
     * Get rule reference duplicate
     *
     * @param object $rows
     * @return object
     */
    public function duplicate(object $rows): object
    {
        // get duplicates rule_reference
        $duplicates = collect($rows)
            ->duplicates('rule_reference')
            ->filter();

        return collect($duplicates);
    }
}
