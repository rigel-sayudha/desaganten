<?php
use App\Models\Domisili;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\DashboardController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\SuratController as PublicSuratController;
use App\Http\Controllers\Admin\SuratPrintController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistikController;

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

Route::get('/test-user-surat', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Route test working',
        'timestamp' => now(),
        'route' => '/test-user-surat'
    ]);
});

Route::get('/test-view-simple', function () {
    return view('test-simple');
});

// Test PDF route
Route::get('/test-pdf', function () {
    try {
        Log::info('PDF Test Environment', [
            'public_path' => public_path(),
            'base_path' => base_path(),
            'storage_path' => storage_path(),
            'app_path' => app_path(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time')
        ]);

        $dompdfExists = class_exists('Barryvdh\DomPDF\Facade\Pdf');
        Log::info('DomPDF Class Check', ['exists' => $dompdfExists]);
        
        $viewExists = view()->exists('admin.surat.pdf.domisili');
        Log::info('View Check', ['exists' => $viewExists]);
        
        $html = '<html><body><h1>Test PDF</h1><p>This is a test PDF document.</p></body></html>';
        
        if (!$dompdfExists) {
            Log::error('DomPDF not found');
            return response()->json(['error' => 'DomPDF facade not found'], 500);
        }

        $testDirs = [
            storage_path('app/dompdf'),
            storage_path('app/dompdf/fonts'),
            public_path(),
            storage_path('fonts/pdf-fonts')
        ];
        
        foreach ($testDirs as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            Log::info('Directory Check', [
                'path' => $dir,
                'exists' => file_exists($dir),
                'writable' => is_writable($dir),
                'permissions' => substr(sprintf('%o', fileperms($dir)), -4)
            ]);
        }
        
        Log::info('Attempting to generate PDF');
        $pdf = PDF::loadHTML($html);
        Log::info('PDF generated successfully');
        
        return $pdf->download('simple-test.pdf');
    } catch (\Exception $e) {
        Log::error('PDF Generation Error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'error' => 'PDF Generation Error',
            'details' => $e->getMessage()
        ], 500);
    }
});

// Test PDF with view
Route::get('/test-pdf-view', function () {
    $testData = (object)[
        'nama' => 'Test User',
        'nik' => '1234567890123456',
        'jenis_kelamin' => 'Laki-laki',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'pekerjaan' => 'Pegawai',
        'alamat' => 'Jl. Test No. 123',
        'status' => 'Belum Menikah',
        'keperluan' => 'Testing PDF',
        'desa' => 'Kemiri',
        'nomor_surat' => 'TEST/001/'.date('m/Y'),
        'kepala_desa' => 'Bapak Kepala Desa'
    ];
    
    try {
        $pdf = PDF::loadView('admin.surat.pdf.domisili', ['surat' => $testData]);
        return $pdf->download('test-domisili.pdf');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => explode("\n", $e->getTraceAsString())
        ], 500);
    }
});

Route::get('/report/system', [\App\Http\Controllers\ReportController::class, 'viewSystemReport'])->name('report.system.view');
Route::get('/report/system/pdf', [\App\Http\Controllers\ReportController::class, 'generateSystemReport'])->name('report.system.pdf');

Route::middleware(['auth', 'ensure.nik'])->group(function () {
    Route::get('/surat/ktp', function () {
        return view('surat.templates.ktp');
    })->name('surat.ktp.form');
    
    Route::get('/surat/kk', function () {
        return view('surat.templates.kk');
    })->name('surat.kk.form');
    
    Route::get('/surat/skck', function () {
        return view('surat.templates.skck');
    })->name('surat.skck.form');
    
    Route::get('/surat/usaha', function () {
        return view('surat.templates.usaha');
    })->name('surat.usaha.form');
    
    Route::get('/surat/kehilangan', function () {
        return view('surat.templates.kehilangan');
    })->name('surat.kehilangan.form');
    
    Route::get('/surat/belum-menikah', function () {
        return view('surat.templates.belum_menikah');
    })->name('surat.belum_menikah.form');
    
    Route::get('/surat/tidak-mampu', function () {
        return view('surat.templates.tidak_mampu');
    })->name('surat.tidak_mampu.form');
    
    Route::get('/surat/kematian', function () {
        return view('surat.templates.kematian');
    })->name('surat.kematian.form');
    
    Route::get('/surat/kelahiran', function () {
        return view('surat.templates.kelahiran');
    })->name('surat.kelahiran.form');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
    
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    
    // User Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // File Access Route
    Route::get('/files/{path}', function($path) {
        $decodedPath = base64_decode($path);
        if (Storage::exists($decodedPath)) {
            return Storage::response($decodedPath);
        }
        abort(404);
    })->where('path', '.*')->name('admin.files.show');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    
    // Profile Management
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/logout', [AdminProfileController::class, 'logout'])->name('admin.logout');
    
    // Test route for profile form
    Route::get('/test-profile', function() {
        return view('test_profile');
    })->name('admin.test-profile');
    
    // Surat Management
    Route::resource('surat', SuratController::class);
    Route::get('/surat/data', [SuratController::class, 'dataSurat']);
    Route::get('/surat/test', function() {
        return response()->json(['message' => 'Test route works', 'user' => auth()->user()]);
    });
    Route::post('/surat/{id}/status', [SuratController::class, 'updateStatus']);
    Route::post('/surat/{id}/complete', [SuratController::class, 'completeVerification'])->name('admin.surat.complete');
    
    // Test Notification Routes
    Route::get('/test-notification', [SuratController::class, 'testNotificationPage'])->name('admin.test-notification');
    Route::post('/test-notification', [SuratController::class, 'testNotification']);
    
    // Verification
    Route::get('/verification', [VerificationController::class, 'index'])->name('admin.verification.index');
    Route::get('/verification/{type}/{id}', [VerificationController::class, 'show'])->name('admin.verification.show');
    Route::post('/verification/{type}/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.updateStage');
    Route::post('/verification/{type}/{id}/note', [VerificationController::class, 'addNote'])->name('admin.verification.addNote');
    
    // Domisili Verification Routes
    Route::get('/verification/domisili/{id}', [VerificationController::class, 'showDomisili'])->name('admin.verification.domisili');
    Route::post('/verification/domisili/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.domisili.updateStage');
    
    // SKCK Verification Routes
    Route::get('/verification/skck/{id}', [VerificationController::class, 'showSkck'])->name('admin.verification.skck');
    Route::post('/verification/skck/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.skck.updateStage');
    
    // Usaha Verification Routes
    Route::get('/verification/usaha/{id}', [VerificationController::class, 'showUsaha'])->name('admin.verification.usaha');
    Route::post('/verification/usaha/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.usaha.updateStage');
    
    // Kehilangan Verification Routes
    Route::get('/verification/kehilangan/{id}', [VerificationController::class, 'showKehilangan'])->name('admin.verification.kehilangan');
    Route::post('/verification/kehilangan/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.kehilangan.updateStage');
    
    // Kematian Verification Routes
    Route::get('/verification/kematian/{id}', [VerificationController::class, 'showKematian'])->name('admin.verification.kematian');
    Route::post('/verification/kematian/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.kematian.updateStage');
    
    // Kelahiran Verification Routes
    Route::get('/verification/kelahiran/{id}', [VerificationController::class, 'showKelahiran'])->name('admin.verification.kelahiran');
    Route::post('/verification/kelahiran/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.kelahiran.updateStage');
    
    // Belum Menikah Verification Routes
    Route::get('/verification/belum_menikah/{id}', [VerificationController::class, 'showBelumMenikah'])->name('admin.verification.belum_menikah');
    Route::post('/verification/belum_menikah/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.belum_menikah.updateStage');
    
    // Tidak Mampu Verification Routes
    Route::get('/verification/tidak_mampu/{id}', [VerificationController::class, 'showTidakMampu'])->name('admin.verification.tidak_mampu');
    Route::post('/verification/tidak_mampu/{id}/stage/{stageNumber}', [VerificationController::class, 'updateStage'])->name('admin.verification.tidak_mampu.updateStage');
    
    // Surat Print
    Route::get('/surat/print-pdf/{type}/{id}', [SuratPrintController::class, 'printPdf']);
    
    // Wilayah Management
    Route::resource('wilayah', WilayahController::class);
    
    // Statistik Management
    Route::prefix('statistik')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\StatistikController::class, 'index'])->name('admin.statistik.index');
        
        // Pekerjaan Management
        Route::get('/pekerjaan', [\App\Http\Controllers\Admin\StatistikController::class, 'pekerjaan'])->name('admin.statistik.pekerjaan');
        Route::get('/pekerjaan/create', [\App\Http\Controllers\Admin\StatistikController::class, 'createPekerjaan'])->name('admin.statistik.pekerjaan.create');
        Route::post('/pekerjaan', [\App\Http\Controllers\Admin\StatistikController::class, 'storePekerjaan'])->name('admin.statistik.pekerjaan.store');
        Route::get('/pekerjaan/{pekerjaan}/edit', [\App\Http\Controllers\Admin\StatistikController::class, 'editPekerjaan'])->name('admin.statistik.pekerjaan.edit');
        Route::put('/pekerjaan/{pekerjaan}', [\App\Http\Controllers\Admin\StatistikController::class, 'updatePekerjaan'])->name('admin.statistik.pekerjaan.update');
        Route::delete('/pekerjaan/{pekerjaan}', [\App\Http\Controllers\Admin\StatistikController::class, 'destroyPekerjaan'])->name('admin.statistik.pekerjaan.destroy');
        
        // Umur Management
        Route::get('/umur', [\App\Http\Controllers\Admin\StatistikController::class, 'umur'])->name('admin.statistik.umur');
        Route::get('/umur/create', [\App\Http\Controllers\Admin\StatistikController::class, 'createUmur'])->name('admin.statistik.umur.create');
        Route::post('/umur', [\App\Http\Controllers\Admin\StatistikController::class, 'storeUmur'])->name('admin.statistik.umur.store');
        Route::get('/umur/{umur}/edit', [\App\Http\Controllers\Admin\StatistikController::class, 'editUmur'])->name('admin.statistik.umur.edit');
        Route::put('/umur/{umur}', [\App\Http\Controllers\Admin\StatistikController::class, 'updateUmur'])->name('admin.statistik.umur.update');
        Route::delete('/umur/{umur}', [\App\Http\Controllers\Admin\StatistikController::class, 'destroyUmur'])->name('admin.statistik.umur.destroy');
        
        // Pendidikan Management
        Route::get('/pendidikan', [\App\Http\Controllers\Admin\StatistikController::class, 'pendidikan'])->name('admin.statistik.pendidikan');
        Route::get('/pendidikan/create', [\App\Http\Controllers\Admin\StatistikController::class, 'createPendidikan'])->name('admin.statistik.pendidikan.create');
        Route::post('/pendidikan', [\App\Http\Controllers\Admin\StatistikController::class, 'storePendidikan'])->name('admin.statistik.pendidikan.store');
        Route::get('/pendidikan/{pendidikan}/edit', [\App\Http\Controllers\Admin\StatistikController::class, 'editPendidikan'])->name('admin.statistik.pendidikan.edit');
        Route::put('/pendidikan/{pendidikan}', [\App\Http\Controllers\Admin\StatistikController::class, 'updatePendidikan'])->name('admin.statistik.pendidikan.update');
        Route::delete('/pendidikan/{pendidikan}', [\App\Http\Controllers\Admin\StatistikController::class, 'destroyPendidikan'])->name('admin.statistik.pendidikan.destroy');

        Route::get('/wilayah', [\App\Http\Controllers\Admin\StatistikWilayahController::class, 'index'])->name('admin.statistik.wilayah.index');
        Route::get('/wilayah/create', [\App\Http\Controllers\Admin\StatistikWilayahController::class, 'create'])->name('admin.statistik.wilayah.create');
        Route::post('/wilayah', [\App\Http\Controllers\Admin\StatistikWilayahController::class, 'store'])->name('admin.statistik.wilayah.store');
        Route::get('/wilayah/{wilayah}/edit', [\App\Http\Controllers\Admin\StatistikWilayahController::class, 'edit'])->name('admin.statistik.wilayah.edit');
        Route::put('/wilayah/{wilayah}', [\App\Http\Controllers\Admin\StatistikWilayahController::class, 'update'])->name('admin.statistik.wilayah.update');
        Route::delete('/wilayah/{wilayah}', [\App\Http\Controllers\Admin\StatistikWilayahController::class, 'destroy'])->name('admin.statistik.wilayah.destroy');
    });
    
    // Laporan Management
    Route::prefix('laporan')->group(function () {
        Route::get('/rekap-surat-keluar', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'index'])->name('admin.laporan.rekap-surat-keluar.index');
        Route::get('/rekap-surat-keluar/create', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'create'])->name('admin.laporan.rekap-surat-keluar.create');
        Route::post('/rekap-surat-keluar', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'store'])->name('admin.laporan.rekap-surat-keluar.store');
        Route::get('/rekap-surat-keluar/{rekapSuratKeluar}', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'show'])->name('admin.laporan.rekap-surat-keluar.show');
        Route::get('/rekap-surat-keluar/{rekapSuratKeluar}/edit', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'edit'])->name('admin.laporan.rekap-surat-keluar.edit');
        Route::put('/rekap-surat-keluar/{rekapSuratKeluar}', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'update'])->name('admin.laporan.rekap-surat-keluar.update');
        Route::delete('/rekap-surat-keluar/{rekapSuratKeluar}', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'destroy'])->name('admin.laporan.rekap-surat-keluar.destroy');
        Route::post('/rekap-surat-keluar/sync', [\App\Http\Controllers\Admin\RekapSuratKeluarController::class, 'syncData'])->name('admin.laporan.rekap-surat-keluar.sync');
    });
});

// Surat Submission Routes
Route::post('/surat/domisili', [PublicSuratController::class, 'domisiliSubmit'])->name('surat.domisili.submit');

// Other surat routes require auth and NIK completion
Route::middleware(['auth', 'ensure.nik'])->group(function () {
    Route::post('/surat/belum-menikah', [PublicSuratController::class, 'belumMenikahSubmit'])->name('surat.belum-menikah.submit');
    Route::post('/surat/tidak-mampu', [PublicSuratController::class, 'tidakMampuSubmit'])->name('surat.tidak-mampu.submit');
    Route::post('/surat/ktp', [PublicSuratController::class, 'ktpSubmit'])->name('surat.ktp.submit');
    Route::post('/surat/kk', [PublicSuratController::class, 'kkSubmit'])->name('surat.kk.submit');
    Route::post('/surat/skck', [PublicSuratController::class, 'skckSubmit'])->name('surat.skck.submit');
    Route::post('/surat/kematian', [PublicSuratController::class, 'kematianSubmit'])->name('surat.kematian.submit');
    Route::post('/surat/kelahiran', [PublicSuratController::class, 'kelahiranSubmit'])->name('surat.kelahiran.submit');
    Route::post('/surat/kehilangan', [PublicSuratController::class, 'kehilanganSubmit'])->name('surat.kehilangan.submit');
    Route::post('/surat/usaha', [PublicSuratController::class, 'usahaSubmit'])->name('surat.usaha.submit');
});

Route::get('/surat/form', function () {
    return view('surat.form');
})->name('surat.form');

// Domisili form - public access (no NIK required)
Route::get('/surat/domisili', function () {
    return view('surat.templates.domisili');
})->name('surat.domisili.form');

// Development routes for incomplete forms
Route::get('/surat/janda', function () {
    return view('surat.templates.janda');
})->name('surat.janda.form');

Route::get('/surat/nikah', function () {
    return view('surat.templates.nikah');
})->name('surat.nikah.form');

Route::get('/surat/pindah', function () {
    return view('surat.templates.pindah');
})->name('surat.pindah.form');

Route::get('/surat/akta', function () {
    return view('surat.templates.akta');
})->name('surat.akta.form');

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
    
    Route::get('/surat/print-pdf/usaha/{id}', function($id) {
        $surat = \App\Models\SuratUsaha::findOrFail($id);
        $pdf = PDF::loadView('admin.surat.pdf.usaha', compact('surat'));
        return $pdf->download("surat-usaha-{$id}.pdf");
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
    Route::post('/surat/{type}/{id}/complete', [\App\Http\Controllers\User\SuratUserController::class, 'completeSurat'])->name('user.surat.complete');
});

// Statistik Public Routes
Route::prefix('statistik')->group(function () {
    Route::get('/', [StatistikController::class, 'statistik'])->name('statistik.main');
    Route::get('/umur', [StatistikController::class, 'umur'])->name('statistik.umur');
    Route::get('/pendidikan', [StatistikController::class, 'pendidikan'])->name('statistik.pendidikan');
    
    // Redirect dari route lama
    Route::get('/wilayah', [StatistikController::class, 'wilayah'])->name('statistik.wilayah');
    Route::get('/usia', [StatistikController::class, 'usia'])->name('statistik.usia');
    Route::get('/pekerjaan', [StatistikController::class, 'pekerjaan'])->name('statistik.pekerjaan');
});

Route::prefix('laporan')->group(function () {
    Route::get('/rekap-surat-keluar', [\App\Http\Controllers\RekapSuratKeluarController::class, 'index'])->name('laporan.rekap-surat-keluar');
});

Route::prefix('debug')->middleware('auth')->group(function () {
    Route::get('/pdf-check', [\App\Http\Controllers\Debug\PdfDebugController::class, 'checkPdfEnvironment'])->name('debug.pdf-check');
    Route::get('/test-template/{type}', [\App\Http\Controllers\Debug\PdfDebugController::class, 'testTemplate'])->name('debug.test-template');
    Route::delete('/clear-logs', [\App\Http\Controllers\Debug\PdfDebugController::class, 'clearErrorLogs'])->name('debug.clear-logs');
});
