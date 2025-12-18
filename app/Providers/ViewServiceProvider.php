<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\KontakInformasi;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $kontak = KontakInformasi::first();

            $view->with('appName', $kontak->nama_aplikasi ?? 'Admin Panel');
        });
    }
}
