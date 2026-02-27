<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use PDOException;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
            ]);

            $credentials = $request->only('email', 'password');

            try {
                if (Auth::attempt($credentials)) {
                    $request->session()->regenerate();
                    
                    // Redirect based on user role
                    $user = Auth::user();
                    if ($user->role === 'admin') {
                        return redirect()->intended('/admin/dashboard');
                    }
                    
                    return redirect()->intended('/');
                }

                return back()->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                ])->withInput($request->except('password'));

            } catch (QueryException $e) {
                Log::error('Database error during login: ' . $e->getMessage());
                return back()->withErrors([
                    'database' => 'Terjadi kesalahan pada database. Mohon coba lagi nanti.'
                ])->withInput($request->except('password'));
            } catch (PDOException $e) {
                Log::error('PDO error during login: ' . $e->getMessage());
                return back()->withErrors([
                    'database' => 'Terjadi kesalahan pada koneksi database. Mohon coba lagi nanti.'
                ])->withInput($request->except('password'));
            } catch (\Exception $e) {
                Log::error('Unexpected error during login: ' . $e->getMessage());
                return back()->withErrors([
                    'error' => 'Terjadi kesalahan. Mohon coba lagi nanti.'
                ])->withInput($request->except('password'));
            }
        } catch (\Exception $e) {
            Log::error('Validation error during login: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan validasi. Mohon cek kembali input Anda.'
            ])->withInput($request->except('password'));
        }
    }

    public function logout(Request $request) {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        } catch (\Exception $e) {
            Log::error('Error during logout: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Terjadi kesalahan saat logout. Mohon coba lagi.');
        }
    }
}
