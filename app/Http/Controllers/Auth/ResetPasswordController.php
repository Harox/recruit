<?php

namespace App\Http\Controllers\Auth;

use App\CompanySetting;
use App\Http\Controllers\Controller;
use App\ThemeSetting;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $setting = CompanySetting::first();
        $frontTheme = ThemeSetting::first();
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'setting' => $setting,'frontTheme'=>$frontTheme]
        );
    }

    protected function redirectTo()
    {
        $user = auth()->user();
        if($user->hasRole('admin')){
            return 'admin/dashboard';
        }
        elseif($user->hasRole('employee')){
            return 'member/dashboard';
        }

    }
}
