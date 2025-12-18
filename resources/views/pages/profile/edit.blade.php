@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-3xl mx-auto py-16 px-6">

    <h1 class="text-2xl font-bold text-green-700 mb-6">
        Edit Profil
    </h1>

    <form action="{{ route('profile.update') }}" method="POST"
          enctype="multipart/form-data"
          class="bg-white shadow rounded-xl p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- FOTO --}}
        <div class="flex items-center gap-6">
            <img alt=""
                src="{{ $user->image
                    ? asset('storage/'.$user->image)
                    : asset('assets/img/admin.jpg') }}"
                class="w-24 h-24 rounded-full object-cover border"
            >
            <input type="file" name="image" class="text-sm">
        </div>

        {{-- NAMA --}}
        <div>
            <label for="" class="font-semibold">Nama</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="w-full border rounded p-2">
        </div>

        {{-- EMAIL --}}
        <div>
            <label for="" class="font-semibold">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="w-full border rounded p-2">
        </div>

        {{-- PHONE --}}
        <div>
            <label for="" class="font-semibold">No. HP</label>
            <input type="text" name="phone"
                   value="{{ old('phone', $user->phone) }}"
                   class="w-full border rounded p-2">
        </div>

        {{-- GENDER --}}
        <div>
            <label for="" class="font-semibold">Jenis Kelamin</label>
            <select name="gender" class="w-full border rounded p-2">
                <option value="">-- Pilih --</option>
                <option value="Laki-laki" {{ $user->gender === 'Laki-laki' ? 'selected' : '' }}>
                    Laki-laki
                </option>
                <option value="Perempuan" {{ $user->gender === 'Perempuan' ? 'selected' : '' }}>
                    Perempuan
                </option>
            </select>
        </div>

        {{-- ROLE --}}
        <div>
            <label for="" class="font-semibold">Role</label>
            <input type="text"
                   value="{{ $user->role }}"
                   disabled
                   class="w-full border rounded p-2 bg-gray-100 cursor-not-allowed">
        </div>

        <div class="flex gap-3">
            <button class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-600">
                Simpan
            </button>

            <a href="{{ route('profile') }}"
               class="px-6 py-2 rounded border hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>

</div>
@endsection
