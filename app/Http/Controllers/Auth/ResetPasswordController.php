<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    // login to chal gya, ab jb reset pswd pa click krta hun to wo sidebar b show krha sath yh dekh
    // isko b apny template k hisab se  bnana meny sidebar kahan include ki hai? app.bladeap
    // wp app utha rha hai mtlb?
    // mtlb app.blade mn change kro to changes ati hain app_auths mn kro to nh changes arti

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    public function showResetForm(Request $request, $token = null)
    {
        // path add kr reset ka
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
