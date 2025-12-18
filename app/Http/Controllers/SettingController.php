<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Setting;

class SettingController extends Controller
{
    /* =========================
     * SECURITY
     * ========================= */
    public function security()
    {
        $settings = Setting::load();

        return view('admin.pengaturan.security', [
            'session_lifetime' => $settings['security']['session_lifetime'] ?? 120,
            'cookie_secure'    => $settings['security']['cookie_secure'] ?? false,
        ]);
    }

    public function saveSecurity(Request $request)
    {
        $settings = Setting::load();

        $settings['security'] = [
            'session_lifetime' => $request->session_lifetime,
            'cookie_secure'    => $request->has('cookie_secure'),
        ];

        Setting::save($settings);

        return back()->with('success', 'Pengaturan keamanan berhasil disimpan.');
    }

    /* =========================
     * MIDTRANS
     * ========================= */
    public function midtrans()
    {
        $settings = Setting::load();

        return view('admin.pengaturan.midtrans', [
            'mode' => $settings['midtrans_mode'] ?? 'sandbox',

            'sandbox' => $settings['midtrans']['sandbox'] ?? [],
            'production' => $settings['midtrans']['production'] ?? [],
        ]);
    }

    public function saveMidtrans(Request $request)
    {
        $settings = Setting::load();

        $settings['midtrans_mode'] = $request->midtrans_mode;

        $settings['midtrans'] = [
            'sandbox' => [
                'client_key'  => $request->sandbox_client,
                'server_key'  => $request->sandbox_server,
                'merchant_id' => $request->sandbox_merchant,
            ],
            'production' => [
                'client_key'  => $request->production_client,
                'server_key'  => $request->production_server,
                'merchant_id' => $request->production_merchant,
            ],
        ];

        Setting::save($settings);

        return back()->with('success', 'Pengaturan Midtrans berhasil disimpan.');
    }
}
