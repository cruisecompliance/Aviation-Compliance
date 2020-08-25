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

    public function getEmail(string $email)
    {

        $user = User::where('email', $email)->first();

        if (!empty($user)) {

            auth()->login($user);

            return response()->json([
                'success' => true,
                'message' => "Auth user - {$user->email}.",
                'user' => Auth::user(),
                'role' => Auth::user()->roles->first(),
                'sme' => RoleName::SME,
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Error",
                'resource' => NULL,
            ]);
        }
    }
}

