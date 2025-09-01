<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile page.
     */
    public function show()
    {
        return view('profile.show');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];
        
        // Add password validation if changing password
        if ($request->filled('current_password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }
        
        $validated = $request->validate($rules);
        
        // Update name and email
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Update password if provided
        if ($request->filled('current_password') && $request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        }
        
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
}
