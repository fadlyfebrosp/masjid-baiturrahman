@extends('admin.components.app')

@section('title', 'Pengaturan Keamanan')

@section('content')
<div class="max-w-3xl">

    <h1 class="text-3xl font-bold text-green-700 mb-6">
        Pengaturan Keamanan
    </h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.pengaturan.security.save') }}">
            @csrf

            <div class="mb-4">
                <label for=" " class="font-semibold">Session Timeout (menit)</label>
                <input type="number" name="session_lifetime"
                       value="{{ $session_lifetime }}"
                       class="border p-2 rounded w-full">
            </div>

            <div class="mb-6 flex items-center justify-between">
                <span class="font-semibold">Cookie Aman (HTTPS)</span>

                <label class="inline-flex items-center cursor-pointer">cookies
                    <input type="checkbox" name="cookie_secure"
                           class="sr-only peer"
                           {{ $cookie_secure ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 peer-checked:bg-green-600 rounded-full"></div>
                    <div class="w-5 h-5 bg-white rounded-full shadow -ml-9 peer-checked:translate-x-5 transition"></div>
                </label>
            </div>

            <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">
                Simpan Keamanan
            </button>
        </form>
    </div>
</div>
@endsection
