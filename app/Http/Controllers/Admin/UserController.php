<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        // dataTable
        if (request()->ajax()) {

            $builder = User::query();

            return datatables()->of($builder)
                ->addIndexColumn()
                ->editColumn('company', function (User $user){
                    return $user->company->name;
                })
                ->editColumn('role', function (User $user){
                    return $user->getRoleNames()->first();
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
                    // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // get companies for select
        $companies = Company::get(['id','name']);

        // get roles for select
        $roles = Role::get(['id','name']);

        // return view with data
        return view('admin.users.index',[
            'companies' => $companies,
            'roles' => $roles
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4',
            'email' => 'required|string|email|min:4|unique:users,email',
            'password' => 'required|string|min:8',
            'status' => 'required|boolean',
            'company' => 'required|numeric',
            'role' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'company_id' => $request->company,
            'role_id' => $request->role,
        ]);

        // ToDo: generate password end send email to new user

        return response()->json([
            'success' => true,
            'message' => "User {$user->email} was added successfully.",
            'resource' => $user,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // return JSON user data
        return response()->json([
            'success' => true,
            'user' =>  $user,
            'company' => $user->company,
            'role' =>  $user->roles->first(),
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4',
            'email' => 'required|string|email|min:4|unique:users,email,' . $user->id,
            'status' => 'required|boolean',
            'company' => 'required|numeric',
            'role' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
            'company_id' => $request->company,

        ]);

        return response()->json([
            'success' => true,
            'message' => "User {$user->email} was update successfully.",
            'resource' => $user,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
