<?php

namespace App\Providers;

use App\Models\Donasi;
use App\Models\KontakInformasi;
use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        /*
        |--------------------------------------------------------------------------
        | UPDATE LAST LOGIN (PALING AMAN)
        |--------------------------------------------------------------------------
        */
        Event::listen(Login::class, function (Login $event) {

            if (Schema::hasColumn('users', 'last_login_at')) {

                User::where('id', $event->user->getAuthIdentifier())
                    ->update([
                        'last_login_at' => now(),
                    ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | KATEGORI PROGRAM (GLOBAL)
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | LOGO (GLOBAL)
        |--------------------------------------------------------------------------
        */
        $logo = asset('assets/img/logo1.png');

        if (Schema::hasTable('kontak_informasis')) {
            $kontak = KontakInformasi::first();

            if ($kontak && $kontak->logo) {
                $logo = asset('storage/' . $kontak->logo);
            }
        }

        View::share('logo', $logo);

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI ADMIN (KHUSUS NAVBAR ADMIN)
        |--------------------------------------------------------------------------
        */
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
    }
}
