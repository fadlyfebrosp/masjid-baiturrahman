<?php

namespace App\Providers;

use App\Models\Donasi;
use App\Models\KontakInformasi;
use App\Models\Program;
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
        | KATEGORI PROGRAM (GLOBAL)
        |--------------------------------------------------------------------------
        */
        $defaultKategori = ['Zakat', 'Infak', 'Sedekah', 'Wakaf', 'Hibah'];

        $kategoriDB = Program::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->toArray();

        $kategoriProgram = array_unique(array_merge($defaultKategori, $kategoriDB));

        View::share('kategoriProgram', $kategoriProgram);

        /*
        |--------------------------------------------------------------------------
        | LOGO (GLOBAL)
        |--------------------------------------------------------------------------
        */
        $kontak = KontakInformasi::first();

        $logo = $kontak && $kontak->logo
            ? asset('storage/' . $kontak->logo)
            : asset('assets/img/logo1.png');

        View::share('logo', $logo);

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI ADMIN (KHUSUS NAVBAR ADMIN)
        |--------------------------------------------------------------------------
        */
        View::composer('admin.components.navbar', function ($view) {

            // badge count
            $notifDonasi = Donasi::where('is_read_admin', false)
                ->whereIn('status', ['pending', 'paid'])
                ->count();

            // 5 terbaru
            $latestDonasi = Donasi::latest()
                ->limit(5)
                ->get();

            $view->with([
                'notifDonasi'  => $notifDonasi,
                'latestDonasi' => $latestDonasi,
            ]);
        });
    }
}
