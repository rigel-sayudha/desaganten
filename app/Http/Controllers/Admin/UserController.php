<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }
    
    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }
    
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email']));
        return redirect()->route('admin.user.index')->with('success', 'User berhasil diupdate');
    }
    
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }
    
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($user->id)
                ]
            ];
            
            // Add password validation if password fields are present
            if ($request->filled('current_password')) {
                $rules['current_password'] = 'required';
                $rules['password'] = 'required|min:8|confirmed';
            }
            
            $validated = $request->validate($rules);
            
            // Check current password if provided
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['current_password' => 'Password saat ini tidak benar']
                    ], 422);
                }
            }
            
            // Update user data
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email']
            ];
            
            // Add password to update data if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            
            $user->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui profil'
            ], 500);
        }
    }
}
