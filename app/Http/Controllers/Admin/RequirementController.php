<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\RequirementVersions as Version;
use Illuminate\Http\Request;
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
        $versions = Version::latest()->get();

        // get requirement data
        $requirements = Requirement::all();

        // return view with data
        return view('admin.requirements.index', [
            'versions' => $versions,
            'requirements' => $requirements,
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
    public function store(Request $request)
    {

//        //> validate the uploaded file
//        $validation = $request->validate([
//            'title' => 'required|string|min:6',
//            'description' => 'string|min:6',
//            'user_file' => 'required|file|mimes:xlsx'
//        ]);

        //> upload file
        $file = $request->file('user_file');
        $file->getClientOriginalName();
        // generate a new file name
        $file_name = 'import_' . date('d.m.Y_H:s') . '.' . $file->getClientOriginalExtension();
        // save file
        $file_path = $request->file('user_file')->storeAs('public', $file_name);
        //<

        //> store file info data
        $version = Version::create([
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
            $requirement[] = Requirement::create([
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

        // redirect back with success status
//        return redirect()->back()->with('status', 'File "'.  $file->getClientOriginalName() .'" import success.');
        return redirect()->route('admin.requirements.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ $version
     * @return \Illuminate\Http\Response
     */
    public function show(Version $version)
    {
        // get requirement data
        $requirements = Requirement::where('version_id', $version->id)->get();

        // return view with data
        return view('admin.requirements.show', [
            'version' => $version,
            'requirements' => $requirements,
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
