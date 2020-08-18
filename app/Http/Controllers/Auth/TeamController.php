<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}

