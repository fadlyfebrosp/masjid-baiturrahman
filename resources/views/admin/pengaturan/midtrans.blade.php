@extends('admin.components.app')

@section('title', 'Pengaturan Midtrans')

@section('content')
<div x-data="{ mode: '{{ $mode }}' }" class="max-w-3xl">

    <h1 class="text-3xl font-bold text-green-700 mb-6">
        Pengaturan Midtrans
    </h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.pengaturan.midtrans.save') }}">
            @csrf

            <div class="mb-4">
                <label for="mode" class="font-semibold">Mode</label>
                <select x-model="mode" name="midtrans_mode"
                        class="border p-2 rounded w-full">
                    <option value="sandbox">Sandbox</option>
                    <option value="production">Production</option>
                </select>
            </div>

            {{-- SANDBOX --}}
            <div x-show="mode === 'sandbox'" class="space-y-4">
                <input type="text" name="sandbox_client"
                       value="{{ $sandbox['client_key'] ?? '' }}"
                       placeholder="Sandbox Client Key"
                       class="border p-2 rounded w-full">

                <input type="text" name="sandbox_server"
                       value="{{ $sandbox['server_key'] ?? '' }}"
                       placeholder="Sandbox Server Key"
                       class="border p-2 rounded w-full">

                <input type="text" name="sandbox_merchant"
                       value="{{ $sandbox['merchant_id'] ?? '' }}"
                       placeholder="Sandbox Merchant ID"
                       class="border p-2 rounded w-full">
            </div>

            {{-- PRODUCTION --}}
            <div x-show="mode === 'production'" class="space-y-4">
                <input type="text" name="production_client"
                       value="{{ $production['client_key'] ?? '' }}"
                       placeholder="Production Client Key"
                       class="border p-2 rounded w-full">

                <input type="text" name="production_server"
                       value="{{ $production['server_key'] ?? '' }}"
                       placeholder="Production Server Key"
                       class="border p-2 rounded w-full">

                <input type="text" name="production_merchant"
                       value="{{ $production['merchant_id'] ?? '' }}"
                       placeholder="Production Merchant ID"
                       class="border p-2 rounded w-full">
            </div>

            <button class="mt-6 bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">
                Simpan Midtrans
            </button>
        </form>
    </div>
</div>
@endsection
