<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    

    /**
     * Override the credentials method to allow login with email, phone, or username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');

        // Determine if login is an email, phone, or username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' 
                  : (is_numeric($login) ? 'phone' : 'username');

        // Return the credentials array with the corresponding field and password
        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email, phone or username is required.',
            'password.required' => 'Password is required.',
        ]);
    }

    /**
     * Handle the user after they are authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect based on the user's identity
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard'); // Adjust route as needed
        }

        return redirect()->route('showAdp'); // Adjust route as needed
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $login = $request->input('login');
        $user = User::where('email', $login)
            ->orWhere('phone', $login)
            ->orWhere('username', $login)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => ['The provided email/phone/username does not exist.'],
            ]);
        }

        throw ValidationException::withMessages([
            'password' => ['The provided password is incorrect.'],
        ]);
    }
}
