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
use App\Http\Controllers\NotificationController;

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

// Public Routes
Route::get('/', function () {
    return view('home');
})->name('home');

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

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
    
    // Notification routes
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    
    // Surat Management
    Route::resource('surat', SuratController::class);
    Route::get('/surat/data', [SuratController::class, 'dataSurat']);
    Route::post('/surat/{id}/status', [SuratController::class, 'updateStatus']);
    
    // Verification
    Route::get('/verification', [VerificationController::class, 'index'])->name('admin.verification.index');
    Route::get('/verification/{type}/{id}', [VerificationController::class, 'show'])->name('admin.verification.show');
    Route::post('/verification/{type}/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.updateStage');
    Route::post('/verification/{type}/{id}/note', [VerificationController::class, 'addNote'])->name('admin.verification.addNote');
    
    // Surat Print
    Route::get('/surat/print-pdf/{type}/{id}', [SuratPrintController::class, 'printPdf']);
    
    // Wilayah Management
    Route::resource('wilayah', WilayahController::class);
});

// Surat Submission Routes
Route::middleware('auth')->group(function () {
    Route::post('/surat/domisili', [SuratController::class, 'domisiliSubmit'])->name('surat.domisili.submit');
    Route::post('/surat/belum-menikah', [PublicSuratController::class, 'belumMenikahSubmit'])->name('surat.belum_menikah.submit');
    Route::post('/surat/tidak-mampu', [PublicSuratController::class, 'tidakMampuSubmit'])->name('surat.tidak_mampu.submit');
    Route::post('/surat/ktp', [SuratController::class, 'ktpSubmit'])->name('surat.ktp.submit');
    Route::post('/surat/kk', [SuratController::class, 'kkSubmit'])->name('surat.kk.submit');
    Route::post('/surat/skck', [SuratController::class, 'skckSubmit'])->name('surat.skck.submit');
    Route::post('/surat/kematian', [SuratController::class, 'kematianSubmit'])->name('surat.kematian.submit');
    Route::post('/surat/kelahiran', [SuratController::class, 'kelahiranSubmit'])->name('surat.kelahiran.submit');
    Route::post('/surat/kehilangan', [SuratController::class, 'kehilanganSubmit'])->name('surat.kehilangan.submit');
});

// Surat Templates
Route::get('/surat/form', function () {
    return view('surat.form');
})->name('surat.form');

Route::get('/surat/ktp', function () {
    return view('surat.templates.ktp');
})->name('surat.ktp.form');

Route::get('/surat/kk', function () {
    return view('surat.templates.kk');
})->name('surat.kk.form');

Route::get('/surat/skck', function () {
    return view('surat.templates.skck');
})->name('surat.skck.form');

Route::get('/surat/domisili', function () {
    return view('surat.templates.domisili');
})->name('surat.domisili.form');

Route::get('/surat/kematian', function () {
    return view('surat.templates.kematian');
})->name('surat.kematian.form');

Route::get('/surat/kelahiran', function () {
    return view('surat.templates.kelahiran');
})->name('surat.kelahiran.form');

Route::get('/surat/belum-menikah', function () {
    return view('surat.templates.belum_menikah');
})->name('surat.belum_menikah.form');

Route::get('/surat/tidak-mampu', function () {
    return view('surat.templates.tidak_mampu');
})->name('surat.tidak_mampu.form');

Route::get('/surat/janda', function () {
    return view('under_development');
})->name('surat.janda.form');

// Admin specific routes for print
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Print PDF
    Route::get('/surat/print-pdf/domisili/{id}', function($id) {
        $surat = Domisili::findOrFail($id);
        $pdf = PDF::loadView('admin.surat.pdf.domisili', compact('surat'));
        return $pdf->download("surat-domisili-{$id}.pdf");
    });
    
    Route::get('/surat/print-pdf/ktp/{id}', function($id) {
        $surat = \App\Models\SuratKtp::findOrFail($id);
        $pdf = PDF::loadView('admin.surat.pdf.ktp', compact('surat'));
        return $pdf->download("surat-ktp-{$id}.pdf");
    });
    
    Route::get('/surat/print-pdf/skcc/{id}', function($id) {
        $surat = \App\Models\SuratSkck::findOrFail($id);
        $pdf = PDF::loadView('admin.surat.pdf.skcc', compact('surat'));
        return $pdf->download("surat-skcc-{$id}.pdf");
    });
    
    Route::get('/surat/print-pdf/kematian/{id}', function($id) {
        $surat = \App\Models\SuratKematian::findOrFail($id);
        $pdf = PDF::loadView('admin.surat.pdf.kematian', compact('surat'));
        return $pdf->download("surat-kematian-{$id}.pdf");
    });
    
    Route::get('/surat/print-pdf/kelahiran/{id}', function($id) {
        $surat = \App\Models\SuratKelahiran::findOrFail($id);
        $pdf = PDF::loadView('admin.surat.pdf.kelahiran', compact('surat'));
        return $pdf->download("surat-kelahiran-{$id}.pdf");
    });
    
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
