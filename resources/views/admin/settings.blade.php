@extends('admin.components.app')

@section('title', 'Pengaturan')

@section('content')

<div x-data="{ tab: 'security', mode: '{{ $settings['midtrans_mode'] }}' }" class="max-w-3xl">

    <h1 class="text-3xl font-bold text-green-700 mb-6">Pengaturan Sistem</h1>

    {{-- ================= NAV ================= --}}
    <div class="flex border-b mb-6">
        <button @click="tab = 'security'"
            :class="tab === 'security' ? 'border-green-600 text-green-700' : 'border-transparent text-gray-500'"
            class="px-4 py-2 font-semibold border-b-2 transition">
            Keamanan
        </button>

        <button @click="tab = 'credential'"
            :class="tab === 'credential' ? 'border-green-600 text-green-700' : 'border-transparent text-gray-500'"
            class="px-4 py-2 font-semibold border-b-2 transition">
            Credential Pembayaran
        </button>
    </div>

    
    <div x-show="tab === 'security'" class="bg-white p-6 rounded-lg shadow">

        <form method="POST" action="{{ route('admin.settings.security') }}">
            @csrf

            <div class="mb-4">
                <label for="" class="font-semibold">Session Timeout (menit)</label>
                <input type="number" name="session_lifetime"
                    value="{{ $settings['session_lifetime'] }}"
                    class="border p-2 rounded w-full">
            </div>

            <div class="mb-4 flex items-center justify-between">
                <label for="" class="font-semibold">Cookie Aman (HTTPS)</label>

                <label for="" class="inline-flex items-center cursor-pointer">cookies
                    <input type="checkbox" name="cookie_secure"
                        class="sr-only peer"
                        {{ $settings['cookie_secure'] ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 peer-checked:bg-green-600 rounded-full peer transition"></div>
                    <div class="w-5 h-5 bg-white rounded-full shadow -ml-9 peer-checked:translate-x-5 transition"></div>
                </label>
            </div>

            <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
        </form>
    </div>

    {{-- ===================================== --}}
    {{-- TAB MIDTRANS CREDENTIAL --}}
    {{-- ===================================== --}}
    <div x-show="tab === 'credential'" class="bg-white p-6 rounded-lg shadow">

        <form method="POST" action="{{ route('admin.settings.credential') }}">
            @csrf

            {{-- MODE SELECT --}}
            <div class="mb-4">
                <label for="mode_midtrans" class="font-semibold">Mode Midtrans</label>
                <select name="midtrans_mode" x-model="mode"
                    class="border p-2 rounded w-full">
                    <option value="sandbox">Sandbox</option>
                    <option value="production">Production</option>
                </select>
            </div>

            {{-- ===================== --}}
            {{-- SANDBOX GROUP --}}
            {{-- ===================== --}}
            <div x-show="mode === 'sandbox'" class="space-y-4">

                <div>
                    <label for="Sandbox_client_key" class="font-semibold">Sandbox Client Key</label>
                    <input type="text" name="midtrans_sandbox_client"
                        value="{{ $settings['midtrans_sandbox_client'] ?? '' }}"
                        class="border p-2 rounded w-full">
                </div>

                <div>
                    <label for="" class="font-semibold">Sandbox Server Key</label>
                    <input type="text" name="midtrans_sandbox_server"
                        value="{{ $settings['midtrans_sandbox_server'] ?? '' }}"
                        class="border p-2 rounded w-full">
                </div>

                <div>
                    <label for="" class="font-semibold">Sandbox Merchant ID</label>
                    <input type="text" name="midtrans_sandbox_merchant"
                        value="{{ $settings['midtrans_sandbox_merchant'] ?? '' }}"
                        class="border p-2 rounded w-full">
                </div>

            </div>

            {{-- ===================== --}}
            {{-- PRODUCTION GROUP --}}
            {{-- ===================== --}}
            <div x-show="mode === 'production'" class="space-y-4">

                <div>
                    <label for="" class="font-semibold">Production Client Key</label>
                    <input type="text" name="midtrans_production_client"
                        value="{{ $settings['midtrans_production_client'] ?? '' }}"
                        class="border p-2 rounded w-full">
                </div>

                <div>
                    <label for="" class="font-semibold">Production Server Key</label>
                    <input type="text" name="midtrans_production_server"
                        value="{{ $settings['midtrans_production_server'] ?? '' }}"
                        class="border p-2 rounded w-full">
                </div>

                <div>
                    <label for="" class="font-semibold">Production Merchant ID</label>
                    <input type="text" name="midtrans_production_merchant"
                        value="{{ $settings['midtrans_production_merchant'] ?? '' }}"
                        class="border p-2 rounded w-full">
                </div>

            </div>

            <button class="mt-5 bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">
                Simpan Credential
            </button>
        </form>
    </div>

</div>

@endsection
