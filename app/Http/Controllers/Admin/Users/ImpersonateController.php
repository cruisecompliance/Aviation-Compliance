<?php

namespace App\Http\Controllers\Admin\Users;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Imperonate user (Login as User)
     *
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(int $user_id)
    {
        $user = User::findOrFail($user_id);

        Auth::user()->impersonate($user);

        if (Auth::user()->hasRole(RoleName::SME)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }

    }

    /**
     * Imperonate user leave
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::user()->leaveImpersonation();

        if (Auth::user()->hasRole(RoleName::SME)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }

    }

}
