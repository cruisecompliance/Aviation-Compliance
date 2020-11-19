<?php

namespace App\Http\Controllers\Components\Flows;

use App\Exports\Requirements\FlowsDataExport;
use App\Http\Controllers\Controller;
use App\Models\Flow;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export Flows Data
     *
     * @param Flow $flow
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Flow $flow)
    {
        // file name
        $fileName = 'export_'. now()->format('d_m_Y') . '.xlsx';

        // generate and download file
        return Excel::download(new FlowsDataExport($flow), $fileName);
    }

}
