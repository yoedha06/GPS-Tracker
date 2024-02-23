<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

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
    protected function redirectPath()
    {
        return '/login';
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.redirectIfNotLoggedIn');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:4'],
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);
        event(new Registered($user));

        Auth::login($user);
    }

    public function update(Request $request)
    {
        $user = $request->user(); // Get the authenticated user

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            // Add any other validation rules as necessary
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request['email'];
        // Update other fields as necessary

        if ($user->isDirty('email')) { // Check if the email has been changed
            $user->email_verified_at = null; // Reset the email verification timestamp
            $user->sendEmailVerificationNotification(); // Trigger the email verification notification
        }

        $user->save();

        return redirect()->back()->with('status', 'Profile updated successfully. Please verify your new email address if you changed it.');
    }


    //login setelah registrasi
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->create($request->all());

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}
