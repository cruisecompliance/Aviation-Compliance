<?php

namespace App\Imports\Companies;

use App\Models\CompanyField;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FieldsImport implements WithStartRow
{
    use Importable;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

}
