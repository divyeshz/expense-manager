<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Mail\ForgotPasswordMail;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
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
            return redirect('dashboard');
        }
        return view('auth.login');
    }

    // Show New User Registration Form
    public function registrationForm()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }
        return view('auth.register');
    }

    // Show Forgot Password Form
    public function forgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect('index');
        }
        return view('auth.forgotPassword');
    }

    // Actual Functionality for Forgot Password
    public function forgotPassword(Request $request)
    {
        // Validate Data
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('forgotPasswordForm')->withErrors($validator);
        }

        $user = User::where('email', $request->email)->first();

        dispatch(function() use ($user){
            Mail::to($user->email)->send(new ForgotPasswordMail());
        });

        if (!$user) {
            return redirect()->route('forgotPasswordForm')->with('error', 'Invaild Credential!!!');
        } else {
            return redirect("/")->with('success', 'We have sent you a mail');
        }


    }

    // Show Change Password Form
    public function changePasswordForm()
    {
        // if (Auth::check()) {
        //     return redirect('index');
        // }
        return view('auth.changePassword');
    }

    // Actual Functionality for Change Password
    public function changePassword(Request $request)
    {

        // Validate Data
        $validate = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|min:6|same:new_password',
        ]);

        // If Validation Fails Than Redirect And Show Validation Error
        if ($validate->fails()) {
            return redirect()->route('changePasswordForm')->withErrors($validate);
        }
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users', function ($attribute, $value, $fail) {
                if (substr($value, -4) !== '.com') {
                    $fail($attribute . ' must end with .com');
                }
            },],
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ]);

        // If Validation Fails Than Redirect And Show Validation Error
        if ($validator->fails()) {
            return redirect('registration')->withErrors($validator);
        }

        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        // Store data Into User Table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        dispatch(function() use ($user, $data){
            Mail::to($user->email)->send(new WelcomeMail($data));
        });

        $min = 10000000;
        $max = 99999999;
        $randomNumber = random_int($min, $max);

        Account::create([
            'name' => $user->name,
            'email' => $user->email,
            'account_number' => $randomNumber,
            'owner_id' => $user->id
        ]);

        return redirect('/')->with('success', 'Register SuccessFully!!!  Now Login.');
    }

    // User Login
    public function login(Request $request)
    {
        // Validate Data
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // If Validation Fails Than Redirect And Show Validation Error
        if ($validate->fails()) {
            return redirect('/')->withErrors($validate);
        }

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
        return redirect('/');
    }
}
