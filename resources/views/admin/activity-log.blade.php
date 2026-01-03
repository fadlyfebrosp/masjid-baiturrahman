@extends('admin.components.app')

@section('title', 'Log Activity')

@section('content')
<div class="mb-6">

    <!-- ================= HEADER ================= -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-2">
        <h1 class="text-2xl md:text-3xl font-bold text-green-700">
            Log Aktivitas
        </h1>

        <nav class="text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-green-700 hover:underline">
                Home
            </a>
            <span class="mx-1">›</span>
            <span class="font-semibold text-gray-800">Log Aktivitas</span>
        </nav>
    </div>

    <!-- ================= DESKTOP TABLE ================= -->
    <div class="hidden md:block bg-white shadow-lg rounded-xl p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600">
                    <th class="py-3 px-4 text-left font-semibold">User</th>
                    <th class="py-3 px-4 text-left font-semibold">Aksi</th>
                    <th class="py-3 px-4 text-left font-semibold">IP</th>
                    <th class="py-3 px-4 text-left font-semibold">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-4 px-4 font-medium text-gray-800">
                        {{ $log->user->name ?? 'Guest' }}
                    </td>
                    <td class="py-4 px-4 text-gray-700">
                        {{ $log->action }}
                    </td>
                    <td class="py-4 px-4 text-gray-700">
                        {{ $log->ip_address }}
                    </td>
                    <td class="py-4 px-4 text-gray-600 whitespace-nowrap">
                        {{ $log->created_at->format('d M Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500">
                        Belum ada log aktivitas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ================= MOBILE CARD ================= -->
    <div class="md:hidden space-y-3">
        @forelse ($logs as $log)
        <div class="bg-white shadow rounded-xl p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-sm text-gray-500">User</p>
                    <p class="font-semibold text-gray-800">
                        {{ $log->user->name ?? 'Guest' }}
                    </p>
                </div>
                <span class="text-xs text-gray-500 whitespace-nowrap">
                    {{ $log->created_at->format('d M Y H:i') }}
                </span>
            </div>

            <div class="text-sm mb-2">
                <p class="text-gray-500">Aksi</p>
                <p class="text-gray-700">
                    {{ $log->action }}
                </p>
            </div>

            <div class="text-sm">
                <p class="text-gray-500">IP Address</p>
                <p class="text-gray-700 font-mono">
                    {{ $log->ip_address }}
                </p>
            </div>
        </div>
        @empty
        <div class="bg-white shadow rounded-xl p-6 text-center text-gray-500">
            Belum ada log aktivitas.
        </div>
        @endforelse
    </div>

    <!-- ================= PAGINATION ================= -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>

</div>
@endsection
