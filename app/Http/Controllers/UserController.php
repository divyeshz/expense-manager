<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    // Show New User Registration Form
    public function loginForm()
    {
        // if(Auth::check()){
        //     return redirect('index');
        // }
        return view('auth.login');
    }

    // Show New User Registration Form
    public function registrationForm()
    {
        // if(Auth::check()){
        //     return redirect('index');
        // }
        return view('auth.register');
    }

    public function forgotPasswordForm()
    {
        if(Auth::check()){
            return redirect('index');
        }
        return view('auth.forgotPassword');
    }

    // Register New User Data Into Database
    public function register(Request $request)
    {
        // dd($request->all());
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
        ];

        Mail::to($request->email)->send(new WelcomeMail($data));

        // // Store data Into User Table
        // User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        return redirect('/dashboard')->with('success', 'Register SuccessFully!!!  Now Login.');
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
            return redirect('dashboard')->withErrors($validate);
        }

        return redirect('/')->with('error', 'Invaild Credential!!!');
    }

    // user Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
