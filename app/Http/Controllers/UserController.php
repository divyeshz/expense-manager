<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\PasswordResetToken;
use App\Mail\ForgotPasswordMail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    // Return Profile View Page
    public function profile()
    {
        return view('pages.profile');
    }

    // Show Login Form
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Show New User Registration Form
    public function registrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // Show Forgot Password Form
    public function forgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.forgotPassword');
    }

    // Actual Functionality for Forgot Password
    public function forgotPassword(Request $request)
    {
        // Validate Data
        $request->validate([
            'email' => 'required|email'
        ]);

        $valid = true;
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $valid = false;
            return redirect()->route('forgotPasswordForm')->with('error', 'Invaild Credential!!!');
        }

        $prt_data = PasswordResetToken::where('email', $request->email)->where('token','!=','')->first();
        if ($prt_data != null) {
            $valid = false;
            return redirect()->route('forgotPasswordForm')->with('error', 'Mail is already sent. check your email!!!');
        }

        if ($valid) {
            $token = Str::random(100);
            PasswordResetToken::insert([
                'email'         => $user->email,
                'token'         => $token,
                'created_at'    => Carbon::now(),
            ]);

            dispatch(function () use ($user, $token) {
                Mail::to($user->email)->send(new ForgotPasswordMail($user, $token));
            });

            return redirect("/")->with('success', 'We have sent you a mail');
        }
    }

    // Show Reset Password Form
    public function resetPasswordForm($token)
    {
        $prt_data = PasswordResetToken::where('token', $token)->first();

        if (!$prt_data || Carbon::now()->subminutes(10) > $prt_data->created_at) {
            return redirect()->back()->with('error', 'Invalid password reset link or link is expired.');
        } else {
            return view('auth.resetpassword', compact('token'));
        }
    }

    // Actual Functionality for Reset Password
    public function resetPassword(Request $request)
    {
        $prt_data = PasswordResetToken::where('token', $request->prt_token)->first();
        $email = $prt_data->email;
        $user = User::where('email', $email)->first();

        if (!$prt_data || Carbon::now()->subminutes(10) > $prt_data->created_at) {
            return redirect()->back()->with('error', 'Invalid password reset link or link expired.');
        } else {

            // Validate Data
            $request->validate([
                'prt_token'         => 'required',
                'password'          => 'required|min:6',
                'confirm_password'  => 'required|min:6|same:password',
            ]);

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $prt_data = PasswordResetToken::where('token', $request->prt_token)->delete();

            return redirect()->route('loginForm')->with('success', 'Password Reset successfully!!!');
        }
    }

    // Show Change Password Form
    public function changePasswordForm()
    {
        return view('auth.changePassword');
    }

    // Actual Functionality for Change Password
    public function changePassword(Request $request)
    {

        // Validate Data
        $request->validate([
            'old_password'          => 'required|min:6',
            'new_password'          => 'required|min:6',
            'confirm_new_password'  => 'required|min:6|same:new_password',
        ]);

        $user = User::where('email', Auth::user()->email)->first();

        if ($user && Hash::check($request->old_password, $user->password)) {
            $newPassword = Hash::make($request->new_password);
            $user->password = $newPassword;
            $user->save();

            return redirect()->route('changePasswordForm')->with('success', 'Password Change SuccessFully!!!');
        } else {
            return redirect()->route('changePasswordForm')->with('error', 'Invaild Credential!!!');
        }
    }

    // Register New User Data Into Database
    public function register(Request $request)
    {
        // Validate Data
        $request->validate([
            'name'              => 'required|string',
            'password'          => 'required|min:6',
            'confirm_password'  => 'required|min:6|same:password',
            'email'             => 'required|email'
        ]);

        $data = [
            'name'      => $request->get('name'),
            'email'     => $request->get('email'),
            'password'  => $request->get('password'),
        ];

        // Store data Into User Table
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        dispatch(function () use ($user, $data) {
            Mail::to($user->email)->send(new WelcomeMail($data));
        });

        $min = 10000000;
        $max = 99999999;
        $randomNumber = random_int($min, $max);

        Account::create([
            'name'              => $user->name,
            'email'             => $user->email,
            'account_number'    => $randomNumber,
            'owner_id'          => $user->id
        ]);

        return redirect()->route('loginForm')->with('success', 'Register SuccessFully!!!  Now Login.');
    }

    // User Login
    public function login(Request $request)
    {
        // Validate Data
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|min:6',
        ]);

        // Attempt for Login With User Credentials
        $credential = $request->only('email', 'password');
        if (Auth::attempt($credential)) {
            return redirect('dashboard')->with('success', 'Login SuccessFully!!!');
        }

        return redirect('/')->with('error', 'Invaild Credential!!!');
    }

    // User Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('loginForm');
    }
}
