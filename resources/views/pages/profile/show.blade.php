@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto py-16 px-6">

    <div class="bg-white shadow rounded-xl p-6">

        <div class="flex items-center gap-6 mb-6">
            <img alt=""
                src="{{ $user->image
                    ? asset('storage/'.$user->image)
                    : asset('assets/img/admin.jpg') }}"
                class="w-28 h-28 rounded-full object-cover border"
            >

            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    {{ $user->name }}
                </h2>
                <p class="text-gray-500">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 text-xs rounded bg-green-100 text-green-700">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <div class="space-y-4 text-gray-700">
            <div>
                <span class="font-semibold">No. HP:</span>
                {{ $user->phone ?? '-' }}
            </div>

            <div>
                <span class="font-semibold">Jenis Kelamin:</span>
                {{ $user->gender ?? '-' }}
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('profile.edit') }}"
               class="inline-block bg-green-700 text-white px-6 py-2 rounded hover:bg-green-600">
                Edit Profil
            </a>
        </div>

    </div>

</div>
@endsection
