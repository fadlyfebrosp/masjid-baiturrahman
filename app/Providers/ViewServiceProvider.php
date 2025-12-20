<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\KontakInformasi;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $appName = null;
            $isSettingEmpty = true;

            // SESUAI migration
            if (Schema::hasTable('kontakinformasis')) {
                $kontak = KontakInformasi::first();

                if (!empty($kontak?->nama_aplikasi)) {
                    $appName = $kontak->nama_aplikasi;
                    $isSettingEmpty = false;
                }
            }

            $view->with([
                'appName' => $appName ?? 'Aplikasi Masjid',
                'isSettingEmpty' => $isSettingEmpty,
            ]);
        });
    }
}
