<?php

namespace App\Http\Controllers\Admin\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Companies\FieldRequest;
use App\Imports\Companies\FieldsValidation;
use App\Models\CompanyField;
use App\Models\CompanyFieldsData;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\Companies\FieldsImport;

class FieldController extends Controller
{
    public function store(FieldRequest $request): JsonResponse
    {
        // get data from file
        $sheets = (new FieldsImport)->toArray($request->file('user_file'));

        // prepare file data
        $fields = (new FieldsValidation())->prepare($sheets[0]);
        $duplicates = (new FieldsValidation())->duplicate($fields);

        // check if isset errors in file fields
        if ($duplicates->isNotEmpty()) {
            return response()->json([
                'duplicate' => $duplicates,
            ]);
        }

        // prepare file
        $file = $request->file('user_file');
        $file_name = 'fields_' . date('d.m.Y_H:s') . '.' . $file->getClientOriginalExtension();
        $folder = 'companies';

        // store file on disk
        $path = Storage::disk('public')->putFileAs($folder, $file, $file_name);

        // store file data in DB
        $version = DB::transaction(function () use ($request, $file_name, $fields) {

            $version = CompanyField::create([
                'file_name' => $file_name,
                'description' => $request->description,
                'company_id' => $request->company_id,
            ]);

            foreach ($fields as $field) {
                CompanyFieldsData::create([
                    'rule_reference' => $field['rule_reference'],
                    'company_manual' => $field['company_manual'],
                    'company_chapter' => $field['company_chapter'],
                    'version_id' => $version->id,
                ]);
            }

            return $version;
        });

        // return JSON success data
        return response()->json([
            'success' => true,
            'message' => "File {$file->getClientOriginalName()} imported successfully.",
        ], 200);

    }
}
