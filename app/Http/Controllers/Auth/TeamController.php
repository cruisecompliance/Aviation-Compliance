<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function login()
    {
        return view('auth.teams.login');
    }


    public function start()
    {
        return view('auth.teams.start');
    }

    public function end()
    {
        return view('auth.teams.end');
    }

    public function silent_start()
    {
        return view('auth.teams.silent_start');
    }

    public function silent_end()
    {
        return view('auth.teams.silent_end');
    }

    /**
     *
     * @param string $azure_email
     * @param string $azure_name
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(string $azure_email, string $azure_name)
    {
        $user = User::where('email', $azure_email)->first();

        if (!empty($user)) {

            $user->update(['azure_name' => $azure_name]);

            auth()->login($user);

            return response()->json([
                'success' => true,
                'message' => "Auth user - {$user->email}.",
                'role' => Auth::user()->roles->first(),
                'sme' => RoleName::SME,
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Error",
                'resource' => NULL,
            ], 500);
        }
    }
}

