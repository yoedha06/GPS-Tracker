<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetPhoneToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
            'username' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:4'],
            'phone' => ['nullable', 'min:4'],
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
        // dd($data); // dump data
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);
        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    //login setelah registrasi
    public function register(Request $request)
    {
        // dd($request->all());
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = $this->create($request->all());

        // Mengecek apakah pengguna mendaftar dengan menggunakan email
        if ($request->email) {
            return redirect('/email/verify')->with('success', 'A verification link has been sent to your email address.');
        } elseif ($request->email) {
            return redirect('/email/verify')->with('error', 'Failed to send verification link. Please try again later.');
        }
        // Mengecek apakah pengguna mendaftar dengan menggunakan nomor telepon
        elseif ($request->phone) {
            // Kirim tautan verifikasi nomor telepon
            $url = "https://app.japati.id/api/send-message";

            $token = Str::random(64);

            $currentTime = Carbon::now()->toDateTimeString();
            // Simpan token ke dalam database
            PasswordResetPhoneToken::updateOrCreate(
                ['phone' => $request->phone], // Kriteria pencarian
                ['token' => $token, 'created_at' => $currentTime] // Data untuk di-update atau dibuat
            );

            $appUrl = route('phone.verify', ['token' => $token]); // Mengarahkan ke route verifikasi dengan menyertakan token

            $data = [
                'gateway' => '6285954906329',
                'number' => $user->phone,
                'type' => 'text',
                'message' => "Click this link to verify your phone: $appUrl?token=" . $token,
            ];

            try {
                $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                    ->post($url, $data);

                if ($response->successful()) {
                    return redirect('/phone/verify')->with('success', 'A verification link has been sent to your phone address.');
                } else {
                    return redirect('/phone/verify')->with('error', 'Failed to send verification link. Please try again later.');
                }
                // Auth::logout();
            } catch (RequestException $e) {
                return redirect('/phone/verify')->with('error', 'Failed to send verification link. Please try again later.');
            }
        }
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

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        }

        // Handle photo upload if a file is present
        if ($request->hasFile('photo')) {
            $photo = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('photos'), $photo);
            $user->photo = $photo;
        }

        $user->save();

        return redirect()->back()->with('status', 'Profile updated successfully. Please verify your new email address if you changed it.');
    }
}
