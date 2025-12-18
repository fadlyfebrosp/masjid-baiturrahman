@extends('admin.components.app')

@section('title', 'Log Activity')

@section('content')
<div class="mb-6">

    <!-- HEADER: TITLE + BREADCRUMB -->
    <div class="flex justify-between items-center mb-4">

        <!-- TITLE -->
        <h1 class="text-3xl font-bold text-green-700">
            Log Aktivitas
        </h1>

        <!-- BREADCRUMB -->
        <nav class="text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}"
               class="hover:text-green-700 hover:underline">
                Home
            </a>
            <span class="mx-1">›</span>
            <span class="font-semibold text-gray-800">
                Log Aktivitas
            </span>
        </nav>

    </div>

    <!-- CARD TABLE -->
    <div class="bg-white shadow-lg rounded-xl p-4 overflow-x-auto">

        <table class="w-full">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600 text-sm">
                    <th class="py-3 px-4 font-semibold text-left">User</th>
                    <th class="py-3 px-4 font-semibold text-left">Aksi</th>
                    <th class="py-3 px-4 font-semibold text-left">IP</th>
                    <th class="py-3 px-4 font-semibold text-left">Waktu</th>
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
                    <td class="py-4 px-4 text-gray-600">
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

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>

</div>
@endsection
