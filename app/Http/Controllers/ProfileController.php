<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): \Illuminate\View\View
    {
        // Calculate how many rooms the user has completed
        $user = $request->user();
        $roomsCompleted = 0;
        
        // This checks your specific database setup safely
        if (isset($user->level_1_score) && $user->level_1_score > 0) $roomsCompleted++;
        if (isset($user->level_2_score) && $user->level_2_score > 0) $roomsCompleted++;
        if (isset($user->level_3_score) && $user->level_3_score > 0) $roomsCompleted++;
        if (isset($user->level_4_score) && $user->level_4_score > 0) $roomsCompleted++;
        if (isset($user->level_5_score) && $user->level_5_score > 0) $roomsCompleted++;
        
        // Fallback just in case your system tracks it as a single number
        if (isset($user->rooms_completed) && $user->rooms_completed > $roomsCompleted) {
            $roomsCompleted = $user->rooms_completed;
        }

        return view('profile.edit', [
            'user' => $user,
            'roomsCompleted' => $roomsCompleted // Pass this to the view!
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            // Checking for 'full_name' since that's what we named the input in the Blade file
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id)
            ],
        ]);

        // If your database column is 'name' instead of 'full_name', we map it here:
        $user->name = $validated['full_name']; 
        $user->email = $validated['email'];

        // If the email changes, we should reset their email verification status (optional but good practice)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }
}