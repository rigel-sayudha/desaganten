<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\BelumMenikah;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('partials.navbar', function ($view) {
            $surat_approved = collect();
            
            if (Auth::check()) {
                $user = Auth::user();
                
                // Get all verified surat for current user
                $domisiliVerified = Domisili::where('nik', $user->nik)
                    ->where('status', 'sudah diverifikasi')
                    ->where('updated_at', '>=', now()->subDays(7)) // Only recent notifications
                    ->get();
                
                $tidakMampuVerified = TidakMampu::where('nik', $user->nik)
                    ->where('status', 'sudah diverifikasi')
                    ->where('updated_at', '>=', now()->subDays(7))
                    ->get();
                
                $belumMenikahVerified = BelumMenikah::where('nik', $user->nik)
                    ->where('status', 'sudah diverifikasi')
                    ->where('updated_at', '>=', now()->subDays(7))
                    ->get();
                
                // Add jenis_surat property for display
                foreach ($domisiliVerified as $surat) {
                    $surat->jenis_surat = 'domisili';
                    $surat_approved->push($surat);
                }
                
                foreach ($tidakMampuVerified as $surat) {
                    $surat->jenis_surat = 'tidak mampu';
                    $surat_approved->push($surat);
                }
                
                foreach ($belumMenikahVerified as $surat) {
                    $surat->jenis_surat = 'belum menikah';
                    $surat_approved->push($surat);
                }
                
                // Sort by updated_at desc
                $surat_approved = $surat_approved->sortByDesc('updated_at');
            }
            
            $view->with('surat_approved', $surat_approved);
        });
    }
}
