<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requirements\RequirementRequest;
//use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\RequirementsData;
use App\Imports\RequirementsImport;
use Maatwebsite\Excel\Facades\Excel;

class RequirementController extends Controller
{
    /**
     * Display a listing of the requirements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get requirement versions data
        $versions = Requirement::latest()->get();

        // return view with data
        return view('admin.requirements.index', [
            'versions' => $versions,
        ]);
    }


    /**
     * Show the form for creating a new requirements.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.requirements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequirementRequest $request)
    {
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
        $path = "../public/storage/".$file_name;

        // get file data
        $array = (new RequirementsImport)->toArray($path);
//        $array = Excel::import(new RequirementsImport, $path, $version->id);

        // save file data
        foreach ($array[0] as $item)
        {
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
        //<

        // redirect to the admin.requirements.index page with success status
        return redirect()->route('admin.requirements.index')->with('status', 'File "'.  $file->getClientOriginalName() .'" imported success.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Requirement $requirement
     * @return \Illuminate\Http\Response
     */
    public function show(Requirement $requirement)
    {
        // get requirement data
        $data = RequirementsData::where('version_id', $requirement->id)->get();

        // return view with data
        return view('admin.requirements.show', [
            'requirement' => $requirement,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function edit(Requirement $requirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requirement $requirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requirement $requirement)
    {
        //
    }
}
