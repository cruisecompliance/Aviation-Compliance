<?php

namespace App\Imports;

use App\Models\RequirementsData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RequirementsImport implements ToModel, WithStartRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new RequirementsData([
            'rule_section' => $row[0],
            'rule_group' => $row[1],
            'rule_reference' => $row[2],
            'rule_title' => $row[3],
            'rule_manual_reference' => $row[4],
            'rule_chapter' => $row[5],
        ]);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 3;
    }

}
