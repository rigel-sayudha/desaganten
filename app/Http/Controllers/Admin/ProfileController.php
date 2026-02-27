<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Update the admin's profile information.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ];
            
            // Add password validation if changing password
            if ($request->filled('current_password')) {
                $rules['current_password'] = ['required'];
                $rules['password'] = ['required', 'confirmed', 'min:8'];
                
                // Verify current password manually for better error handling
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password saat ini tidak valid',
                        'errors' => [
                            'current_password' => ['Password saat ini tidak benar']
                        ]
                    ], 422);
                }
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
            
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat kesalahan validasi',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Admin profile update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }
    
    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout');
    }
}
