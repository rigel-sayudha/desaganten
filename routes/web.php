<?php
use App\Models\Domisili;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\SuratPrintController;
use App\Http\Controllers\Admin\WilayahController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/surat/ktp', function () {
    return view('surat.templates.ktp');
});
Route::get('/surat/kk', function () {
    return view('surat.templates.kk');
});
Route::get('/surat/skck', function () {
    return view('surat.templates.skck');
});
Route::get('/surat/kehilangan', function () {
    return view('surat.kehilangan');
})->name('surat.kehilangan');
Route::get('/surat/usaha', function () {
    return view('surat.templates.usaha');
})->name('surat.usaha');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('auth.profile');
    })->name('profile');
});
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/surat/data', [\App\Http\Controllers\Admin\SuratController::class, 'dataSurat'])->name('admin.surat.data');
    Route::resource('surat', \App\Http\Controllers\Admin\SuratController::class)->names([
        'index' => 'admin.surat.index',
        'create' => 'admin.surat.create',
        'store' => 'admin.surat.store',
        'show' => 'admin.surat.show',
        'edit' => 'admin.surat.edit',
        'update' => 'admin.surat.update',
        'destroy' => 'admin.surat.destroy'
    ]);
});

Route::post('/surat/kehilangan/submit', [SuratController::class, 'kehilanganSubmit'])->name('surat.kehilangan.submit');

Route::post('/surat/ktp/submit', [SuratController::class, 'ktpSubmit'])->name('surat.ktp.submit');

Route::post('/surat/kematian/submit', [SuratController::class, 'kematianSubmit'])->name('surat.kematian.submit');

Route::post('/surat/kk/submit', [SuratController::class, 'kkSubmit'])->name('surat.kk.submit');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('user', UserController::class, ['as' => 'admin']);
    Route::get('/surat/print-pdf/domisili/{id}', [SuratPrintController::class, 'printDomisili'])->name('admin.surat.print.domisili');
    Route::get('/surat/print-pdf/ktp/{id}', [SuratPrintController::class, 'printKtp'])->name('admin.surat.print.ktp');
    Route::get('/surat/print-pdf/kk/{id}', [SuratPrintController::class, 'printKk'])->name('admin.surat.print.kk');
    Route::get('/surat/print-pdf/skck/{id}', [SuratPrintController::class, 'printSkck'])->name('admin.surat.print.skck');
    Route::get('/surat/print-pdf/kematian/{id}', [SuratPrintController::class, 'printKematian'])->name('admin.surat.print.kematian');
    Route::get('/surat/print-pdf/kelahiran/{id}', [SuratPrintController::class, 'printKelahiran'])->name('admin.surat.print.kelahiran');
});
Route::post('/surat/kelahiran/submit', [SuratController::class, 'kelahiranSubmit'])->name('surat.kelahiran.submit');
Route::post('/surat/skck/submit', [SuratController::class, 'skckSubmit'])->name('surat.skck.submit');
Route::post('/surat/domisili/submit', function (\Illuminate\Http\Request $request) {
    if (!Auth::check()) {
        return response()->view('auth.force_login');
    }
    $request->validate([
        'nik' => 'required',
        'nama' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required',
        'jenis_kelamin' => 'required',
        'kewarganegaraan' => 'required',
        'agama' => 'required',
        'status' => 'required',
        'pekerjaan' => 'required',
        'alamat' => 'required',
        'keperluan' => 'required',
    ]);
    Domisili::create([
        'nik' => $request->nik,
        'nama' => $request->nama,
        'tempat_lahir' => $request->tempat_lahir,
        'tanggal_lahir' => $request->tanggal_lahir,
        'jenis_kelamin' => $request->jenis_kelamin,
        'kewarganegaraan' => $request->kewarganegaraan,
        'agama' => $request->agama,
        'status' => $request->status,
        'pekerjaan' => $request->pekerjaan,
        'alamat' => $request->alamat,
        'keperluan' => $request->keperluan,
        'status_pengajuan' => 'Menunggu Verifikasi',
    ]);
    return redirect('/surat/domisili')->with('success', 'Permohonan surat keterangan domisili berhasil diajukan.');
});

Route::get('/admin/surat/preview-domisili', function() {
    $data = Domisili::orderBy('created_at', 'desc')->get();
    return response()->json($data);
});
Route::get('/admin/surat/preview-data/{jenis}', [SuratController::class, 'previewData']);
Route::get('/admin/surat/detail/{id}', [SuratController::class, 'detail']);

Route::get('/surat/form', function () {
    return view('surat.form');
});

Route::get('/surat/ktp', function () {
    return view('surat.templates.ktp');
})->name('surat.ktp');

Route::get('/surat/kk', function () {
    return view('surat.templates.kk');
})->name('surat.kk');

Route::get('/surat/skck', function () {
    return view('surat.templates.skck');
})->name('surat.skck');


Route::get('/surat/domisili', function () {
    return view('surat.templates.domisili');
})->name('surat.domisili');

Route::get('/surat/kematian', function () {
    return view('surat.templates.kematian');
})->name('surat.kematian');

Route::get('/surat/kelahiran', function () {
    return view('surat.templates.kelahiran');
})->name('surat.kelahiran');


Route::get('/surat/janda', function () {
    return view('surat.templates.janda');
})->name('surat.janda');

Route::get('/surat/akta', function () {
    return view('surat.templates.akta');
})->name('surat.akta');

Route::get('/surat/nikah', function () {
    return view('surat.templates.nikah');
})->name('surat.nikah');

Route::get('/surat/kehilangan', function () {
    return view('surat.templates.kehilangan');
})->name('surat.kehilangan');

Route::post('/surat/store', [SuratController::class, 'store'])->name('surat.store');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::delete('/surat/{id}/delete', [SuratController::class, 'destroy']);
    Route::post('/surat/{id}/status', [SuratController::class, 'updateStatus']);
    Route::resource('wilayah', WilayahController::class, [
        'as' => 'admin'
    ])->except(['show']);
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
});

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return view('home');
});

Route::get('/statistik/wilayah', function () {
    $wilayah = \App\Models\Wilayah::all();
    return view('statistik.wilayah', compact('wilayah'));
});


Route::get('/admin/login', function () {
    return view('auth.login_admin');
})->name('admin.login')->middleware('guest');

Route::post('/admin/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        // Cek jika user bukan admin, logout dan tolak
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Hanya admin yang dapat login di halaman ini.');
        }
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }
    return back()->with('error', 'Email atau password salah.');
})->middleware('guest');

Route::post('/admin/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin/login')->with('success', 'Anda telah berhasil logout sebagai admin.');
})->name('admin.logout')->middleware('auth');

Route::get('/surat/form', function () {
    return view('surat.form');
});

Route::post('/surat/store', function (\Illuminate\Http\Request $request) {
    return redirect('/surat/form')->with('success', 'Surat berhasil diajukan.');
})->name('surat.store');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('wilayah', WilayahController::class, [
        'as' => 'admin'
    ])->except(['show']);
    
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
});
