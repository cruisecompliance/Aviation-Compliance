<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Enums\RoleName;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Determine the redirect URL after login
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (Auth::user()->hasRole(RoleName::SME)) {
            return route('admin.dashboard');
        } else {
            return route('user.dashboard');
        }
    }

    /**
     * @return mixed
     */
    public function redirectToProvider()
    {
        return Socialite::with('azure')->redirect();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        $azureUser = Socialite::with('azure')->user();

        $user = User::where('email', $azureUser->user['mail'])->first();

        if(!empty($user)){

            auth()->login($user);

            if (Auth::user()->hasRole(RoleName::SME)) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }

        } else {

            return redirect()->back();
            
        }
    }
}
