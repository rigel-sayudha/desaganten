<?php
use App\Models\Domisili;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\VerificationController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\SuratController as PublicSuratController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('auth.profile');
    })->name('profile');
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/surat/data', [\App\Http\Controllers\Admin\SuratController::class, 'dataSurat'])->name('admin.surat.data');
    Route::resource('surat', \App\Http\Controllers\Admin\SuratController::class)->names([
        'index' => 'admin.surat.index',
        'create' => 'admin.surat.create',
        'store' => 'admin.surat.store',
        'show' => 'admin.surat.show',
        'edit' => 'admin.surat.edit',
        'update' => 'admin.surat.update',
        'destroy' => 'admin.surat.destroy',
    ]);
    
    // Verification routes
    Route::get('/verification', [VerificationController::class, 'index'])->name('admin.verification.index');
    Route::get('/verification/{type}/{id}', [VerificationController::class, 'show'])->name('admin.verification.show');
    Route::post('/verification/{type}/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.updateStage');
    Route::post('/verification/{type}/{id}/note', [VerificationController::class, 'addNote'])->name('admin.verification.addNote');
});

Route::middleware('auth')->group(function () {
    Route::post('/surat/kehilangan/submit', [SuratController::class, 'kehilanganSubmit'])->name('surat.kehilangan.submit');
    Route::post('/surat/ktp/submit', [SuratController::class, 'ktpSubmit'])->name('surat.ktp.submit');
    Route::post('/surat/kematian/submit', [SuratController::class, 'kematianSubmit'])->name('surat.kematian.submit');
    Route::post('/surat/kk/submit', [SuratController::class, 'kkSubmit'])->name('surat.kk.submit');
    Route::post('/surat/kelahiran/submit', [SuratController::class, 'kelahiranSubmit'])->name('surat.kelahiran.submit');
    Route::post('/surat/skck/submit', [SuratController::class, 'skckSubmit'])->name('surat.skck.submit');
    Route::post('/surat/domisili/submit', [SuratController::class, 'domisiliSubmit'])->name('surat.domisili.submit');
    Route::post('/surat/belum-menikah/submit', [PublicSuratController::class, 'belumMenikahSubmit'])->name('surat.belum-menikah.submit');
    Route::post('/surat/tidak-mampu/submit', [PublicSuratController::class, 'tidakMampuSubmit'])->name('surat.tidak-mampu.submit');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('user', UserController::class, ['as' => 'admin']);
    Route::get('/surat/print-pdf/domisili/{id}', [SuratPrintController::class, 'printDomisili'])->name('admin.surat.print.domisili');
    Route::get('/surat/print-pdf/ktp/{id}', [SuratPrintController::class, 'printKtp'])->name('admin.surat.print.ktp');
    Route::get('/surat/print-pdf/kk/{id}', [SuratPrintController::class, 'printKk'])->name('admin.surat.print.kk');
    Route::get('/surat/print-pdf/skck/{id}', [SuratPrintController::class, 'printSkck'])->name('admin.surat.print.skck');
    Route::get('/surat/print-pdf/kematian/{id}', [SuratPrintController::class, 'printKematian'])->name('admin.surat.print.kematian');
    Route::get('/surat/print-pdf/kelahiran/{id}', [SuratPrintController::class, 'printKelahiran'])->name('admin.surat.print.kelahiran');
    Route::get('/surat/print-pdf/belum_menikah/{id}', [SuratPrintController::class, 'printBelumMenikah'])->name('admin.surat.print.belum_menikah');
    Route::get('/surat/print-pdf/tidak_mampu/{id}', [SuratPrintController::class, 'printTidakMampu'])->name('admin.surat.print.tidak_mampu');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/surat/preview-domisili', function() {
        $data = Domisili::orderBy('created_at', 'desc')->get();
        return response()->json($data);
    });
    Route::get('/admin/surat/preview-data/{jenis}', [SuratController::class, 'previewData']);
    Route::get('/admin/surat/detail/{id}', [SuratController::class, 'detail']);
});

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

Route::get('/surat/belum-menikah', function () {
    return view('surat.templates.belum_menikah');
})->name('surat.belum-menikah');

Route::get('/surat/tidak-mampu', function () {
    return view('surat.templates.tidak_mampu');
})->name('surat.tidak-mampu');


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
    Route::get('/surat', [SuratController::class, 'index']);
    Route::delete('/surat/{id}/delete', [SuratController::class, 'destroy']);
    Route::post('/surat/{id}/status', [SuratController::class, 'updateStatus']);
    Route::resource('wilayah', WilayahController::class, [
        'as' => 'admin'
    ])->except(['show']);
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Profile management routes
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('wilayah', WilayahController::class, [
        'as' => 'admin'
    ])->except(['show']);
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// User Surat Routes
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/surat', [\App\Http\Controllers\User\SuratUserController::class, 'index'])->name('user.surat.index');
    Route::get('/surat/{type}/{id}', [\App\Http\Controllers\User\SuratUserController::class, 'show'])->name('user.surat.show');
    Route::get('/surat/{type}/{id}/print', [\App\Http\Controllers\User\SuratUserController::class, 'printPdf'])->name('user.surat.print');
});
