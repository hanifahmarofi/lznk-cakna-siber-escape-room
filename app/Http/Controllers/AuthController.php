<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Handle the Registration Submission
    public function registerPost(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'agent_id' => 'required|string|max:50|unique:users', // Custom ID
            'password' => 'required|string|min:6|confirmed', // Ensures password matches password_confirmation
        ]);

        // 2. Create the new Agent in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'agent_id' => $request->agent_id,
            'password' => Hash::make($request->password), // Securely hash the password
        ]);

        // 3. Log the user in immediately
        Auth::login($user);

        // 4. Redirect to Mission Control
        return redirect()->route('dashboard');
    }

    // Handle the Login Submission
    public function loginPost(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'agent_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Attempt to log in using the custom agent_id
        $credentials = [
            'agent_id' => $request->agent_id,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // 3. If login fails, send them back with an error
        return back()->withErrors([
            'agent_id' => 'ACCESS DENIED: Invalid Agent ID or Authorization Key.',
        ]);
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}