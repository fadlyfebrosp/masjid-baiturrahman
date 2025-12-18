<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Setting;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $settings = Setting::load();

        if (!$settings) {
            return;
        }

        $mode = $settings['midtrans_mode'] ?? 'sandbox';
        $midtrans = $settings['midtrans'][$mode] ?? [];

        config([
            'midtrans.server_key'   => $midtrans['server_key'] ?? '',
            'midtrans.client_key'   => $midtrans['client_key'] ?? '',
            'midtrans.merchant_id'  => $midtrans['merchant_id'] ?? '',
            'midtrans.is_production' => $mode === 'production',
        ]);
    }
}
