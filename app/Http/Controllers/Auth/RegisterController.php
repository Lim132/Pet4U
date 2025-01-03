<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'digits_between:10,15', 'unique:users'], // Validates phone as a string of digits between 10 and 15 characters
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Confirmed rule is fine for password
            'age' => ['nullable', 'integer', 'min:16'], // Allows null values for age, since it may not be required
            'gender' => ['nullable', 'string', 'in:male,female,other'], // You can customize the acceptable gender values
            'address' => ['nullable', 'string', 'max:255'],
            // Optional: Set default in database or here as an allowed value
            'role' => ['in:customer,admin'], // Add other roles if needed
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // 设置默认头像路径
       // $defaultAvatarPath = 'images/image1.png';
        
        return User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'age' => $data['age'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'role' => 'customer', // Default identity set to "customer"
            'avatar' => null, // 设置默认头像路径
        ]);
    }
}
