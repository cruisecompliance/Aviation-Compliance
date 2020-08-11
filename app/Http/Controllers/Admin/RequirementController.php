<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requirements\RequirementRequest;
use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\RequirementsData;
use App\Imports\RequirementsImport;
use App\Services\ColorDiff;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class RequirementController extends Controller
{
    /**
     * Display a listing of the requirements.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        // get latest requirement
        $versions = Requirement::latest()->get();

        // dataTable
        if (request()->ajax()) {

            // get last version of requirements data
            $builder = RequirementsData::where('version_id', $versions->first()->id);

            return datatables()->of($builder)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.requirements.history', $row->rule_reference) . '" class="edit btn btn-primary btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

//            $builder = Requirement::query();
//
//            return datatables()->of($builder)
//                ->addIndexColumn()
//                ->addColumn('action', function ($row) {
//                    $btn = '<a href="'.route('admin.requirements.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
//                    return $btn;
//                })
//                ->editColumn('created_at', function ($row) {
//                    return $row->created_at ? with(new Carbon($row->created_at))->format('d.m.Y H:i:s') : '';
//                })
//                ->filterColumn('created_at', function ($query, $keyword) {
//                    $query->whereRaw("DATE_FORMAT(created_at,'%d.%m.%Y %H:%i:%s') like ?", ["%$keyword%"]);
//                })
//                ->rawColumns(['action'])
//                ->make(true);
        }


        // return view
        return view('admin.requirements.index', [
            'versions' => $versions
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $rule_reference
     * @return \Illuminate\Http\Response
     */
    public function history(string $rule_reference)
    {
        // get requirement history
        $history = RequirementsData::where('rule_reference', $rule_reference)->latest()->get();

        // color diff
        // $historyColorDiff = (new ColorDiff())->ColorDiff($history);

        // return view with data
        return view('admin.requirements.history', [
            'history' => $history,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //> request validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|unique:requirements',
            'description' => 'nullable|string|min:4',
            'user_file' => 'required|file|mimes:xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }


        //> upload file
        $file = $request->file('user_file');
        $file->getClientOriginalName();
        // generate a new file name
        $file_name = 'import_' . date('d.m.Y_H:s') . '.' . $file->getClientOriginalExtension();
        // save file
        $file_path = $request->file('user_file')->storeAs('public', $file_name);
        //<

        //> store file info data
        $version = Requirement::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $file_name,
            'file_path' => $file_path,
        ]);
        //<

        //> import data from file
//        $path = "../public/storage/file.xlsx";
        $path = "../public/storage/" . $file_name;

        // import requirements data
        // $array = Excel::import(new RequirementsImport, $path);

        // get file data
        $array = (new RequirementsImport)->toArray($path);

        // save file data (where array[key] is a sheet)
        foreach ($array[1] as $item) {
            if (!empty($item[0]) && !empty($item[1])) {

                if (!empty($item[2])) {
                    $requirement[] = RequirementsData::create([
                        'rule_section' => $item[0],
                        'rule_group' => $item[1],
                        'rule_reference' => $item[2],
                        'rule_title' => $item[3],
                        'rule_manual_reference' => $item[4],
                        'rule_chapter' => $item[5],
                        'version_id' => $version->id
                    ]);
                }

            }
        }
        //<

        return response()->json([
            'success' => true,
            'message' => "File {$file->getClientOriginalName()} was imported successfully.",
            'resource' => $version,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Requirement $requirement
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show(Requirement $requirement)
    {

        // dataTable
        if (request()->ajax()) {

            $builder = RequirementsData::where('version_id', $requirement->id);

            return datatables()->of($builder)
                ->addIndexColumn()
                ->make(true);
        }

        // return view with data
        return view('admin.requirements.show', [
            'requirement' => $requirement,
        ]);
    }

}
