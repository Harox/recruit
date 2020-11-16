<?php

namespace App\Http\Controllers\Auth;

use App\CompanySetting;
use App\Http\Controllers\Controller;
use App\ThemeSetting;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers, AppBoot;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (!$this->isLegal()) {
            return redirect('verify-purchase');
        }
        $setting = CompanySetting::first();
        $frontTheme = ThemeSetting::first();
        return view('auth.login', compact('setting', 'frontTheme'));
    }

    protected function redirectTo()
    {
        return 'admin/dashboard';
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
}
