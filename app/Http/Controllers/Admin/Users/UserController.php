<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


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

            $builder = User::with('company')->with('roles')->select('users.*');

            return datatables()->of($builder)
                ->addIndexColumn()
                ->editColumn('company', function (User $user) {
                    return $user->company ? $user->company->name : '';
                })
                ->editColumn('roles', function (User $user) {
//                    return $user->getRoleNames()->first();
//                    return $user->roles->pluck('name')->implode('<br>');
                    return $user->roles->first() ? $user->roles->first()->name : '';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('admin.users.impersonate.login', $row->id) . '" class="btn btn-success btn-sm mr-1">Log In</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // get companies for select input
        $companies = Company::get(['id', 'name']);

        // get roles for select input
        $roles = Role::get(['name']);

        // return view with data
        return view('admin.users.index', [
            'companies' => $companies,
            'roles' => $roles
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4',
            'email' => 'required|string|email|min:4|unique:users,email',
            'password' => 'required|string|min:8',
            'status' => 'required|boolean',
            'company' => 'required|numeric',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'company_id' => $request->company,
            ])->assignRole($request->role);

            return response()->json([
                'success' => true,
                'message' => "User {$user->email} was added successfully.",
                'resource' => $user,
            ]);
        });
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // return JSON user data
        return response()->json([
            'success' => true,
            'user' => $user,
            'company' => $user->company,
            'role' => $user->roles->first(),
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4',
            'email' => 'required|string|email|min:4|unique:users,email,' . $user->id,
            'status' => 'required|boolean',
            'company' => 'required|numeric',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        return DB::transaction(function () use ($request, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
                'company_id' => $request->company,
            ]);

            $user->syncRoles([$request->role]);

            return response()->json([
                'success' => true,
                'message' => "User {$user->email} was update successfully.",
                'resource' => $user,
            ]);
        });
    }

}
