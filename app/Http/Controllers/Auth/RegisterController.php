<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use PDOException;

class RegisterController extends Controller
{
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'user', 
                ]);

                Auth::login($user);
                
                return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang di Desa Ganten.');
                
            } catch (QueryException $e) {
                Log::error('Database error during registration: ' . $e->getMessage());
                return back()->withErrors([
                    'database' => 'Terjadi kesalahan pada database. Mohon coba lagi nanti.'
                ])->withInput($request->except('password', 'password_confirmation'));
            } catch (PDOException $e) {
                Log::error('PDO error during registration: ' . $e->getMessage());
                return back()->withErrors([
                    'database' => 'Terjadi kesalahan pada koneksi database. Mohon coba lagi nanti.'
                ])->withInput($request->except('password', 'password_confirmation'));
            } catch (\Exception $e) {
                Log::error('User creation error: ' . $e->getMessage());
                return back()->withErrors([
                    'error' => 'Terjadi kesalahan saat membuat akun. Mohon coba lagi nanti.'
                ])->withInput($request->except('password', 'password_confirmation'));
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error during registration: ' . implode(', ', $e->errors()));
            return back()->withErrors($e->errors())->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
