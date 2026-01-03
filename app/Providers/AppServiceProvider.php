<?php

namespace App\Providers;

use App\Models\Donasi;
use App\Models\Program;
use App\Models\User;
use App\Models\KontakInformasi;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --------------------------------------------------------------------
        // 1. UPDATE LAST LOGIN USER
        // --------------------------------------------------------------------
        Event::listen(Login::class, function (Login $event) {
            if (Schema::hasColumn('users', 'last_login_at')) {
                User::where('id', $event->user->getAuthIdentifier())
                    ->update([
                        'last_login_at' => now(),
                    ]);
            }
        });

        // --------------------------------------------------------------------
        // 2. KATEGORI PROGRAM (GLOBAL)
        // --------------------------------------------------------------------
        $defaultKategori = ['Zakat', 'Infak', 'Sedekah', 'Wakaf', 'Hibah'];
        $kategoriProgram = $defaultKategori;

        if (Schema::hasTable('programs')) {
            $kategoriDB = Program::select('kategori')
                ->distinct()
                ->pluck('kategori')
                ->toArray();

            $kategoriProgram = array_values(
                array_unique(array_merge($defaultKategori, $kategoriDB))
            );
        }

        View::share('kategoriProgram', $kategoriProgram);

        // --------------------------------------------------------------------
        // 3. NOTIFIKASI ADMIN (NAVBAR ADMIN)
        // --------------------------------------------------------------------
        View::composer('admin.components.navbar', function ($view) {

            $notifDonasi  = 0;
            $latestDonasi = collect();

            if (Schema::hasTable('donasis')) {
                $notifDonasi = Donasi::where('is_read_admin', false)
                    ->whereIn('status', ['pending', 'paid'])
                    ->count();

                $latestDonasi = Donasi::latest()
                    ->limit(5)
                    ->get();
            }

            $view->with([
                'notifDonasi'  => $notifDonasi,
                'latestDonasi' => $latestDonasi,
            ]);
        });

        // --------------------------------------------------------------------
        // 4. LOGO UNTUK SEMUA HALAMAN ERROR (401,403,404,419,500)
        // --------------------------------------------------------------------
        View::composer('errors::*', function ($view) {
            try {
                $view->with('logo', $this->getLogo());
            } catch (\Throwable $e) {
                // fallback PALING AMAN
                $view->with('logo', asset('assets/img/logo.png'));
            }
        });
    }

    /**
     * Ambil logo dari database dengan fallback aman
     */
    private function getLogo(): string
    {
        // Cegah error saat migrate / DB belum siap
        if (!Schema::hasTable('kontak_informasis')) {
            return asset('assets/img/logo.png');
        }

        $kontak = KontakInformasi::first();

        if (
            $kontak &&
            $kontak->logo &&
            Storage::disk('public')->exists($kontak->logo)
        ) {
            return asset('storage/' . $kontak->logo);
        }

        return asset('assets/img/logo.png');
    }
}
