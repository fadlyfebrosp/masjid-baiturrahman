@extends('admin.components.app')

@section('title', 'Profil Admin')

@section('content')
<div class="max-w-3xl mx-auto mt-8" x-data="{ editMode: false }">
  <h1 class="text-3xl font-bold text-green-700 mb-6">Profil Admin</h1>

  @if (session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  {{-- TAMPILAN VIEW MODE --}}
  <div x-show="!editMode" x-transition class="bg-white p-6 rounded-xl shadow">
    <div class="flex items-center gap-6 mb-6">
      <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('assets/img/avatars/20.png') }}"
           alt="Foto Profil" class="w-24 h-24 rounded-full object-cover border border-green-500">
      <div>
        <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
        <p class="text-gray-600">{{ $user->email }}</p>
        <p class="text-sm text-gray-500">Anda Saat Ini Sebagai: {{ $user->role }}</p>
      </div>
    </div>

    <div class="space-y-3">
      <div>
        <span class="font-semibold text-gray-700">Telepon:</span>
        <p class="text-gray-600">{{ $user->phone ?? '-' }}</p>
      </div>
      <div>
        <span class="font-semibold text-gray-700">Gender:</span>
        <p class="text-gray-600">{{ $user->gender ?? '-' }}</p>
      </div>
    </div>

    <div class="flex justify-end mt-6">
      <button @click="editMode = true" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
        Edit Profil
      </button>
    </div>
  </div>

  {{-- TAMPILAN EDIT MODE --}}
  <form x-show="editMode" x-transition action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow mt-4">
    @csrf
    <h2 class="text-lg font-semibold mb-4 text-gray-700">Edit Profil</h2>

    <div class="mb-4">
      <label for="name" class="block font-semibold mb-1">Nama</label>
      <input type="text" name="name" id="name" value="{{ $user->name }}" class="w-full border rounded-lg p-2" required>
    </div>

    <div class="mb-4">
      <label for="email" class="block font-semibold mb-1">Email</label>
      <input type="email" name="email" id="email" value="{{ $user->email }}" class="w-full border rounded-lg p-2" required>
    </div>

    <div class="mb-4">
      <label for="phone" class="block font-semibold mb-1">Telepon</label>
      <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="w-full border rounded-lg p-2">
    </div>

    <div class="mb-4">
      <label for="gender" class="block font-semibold mb-1">Gender</label>
      <select name="gender" id="gender" class="w-full border rounded-lg p-2">
        <option value="">Pilih Gender</option>
        <option value="Laki-laki" @if($user->gender == 'Laki-laki') selected @endif>Laki-laki</option>
        <option value="Perempuan" @if($user->gender == 'Perempuan') selected @endif>Perempuan</option>
      </select>
    </div>

    <div class="mb-4">
      <label for="image" class="block font-semibold mb-1">Foto Profil</label>
      <input type="file" name="image" id="image" class="w-full border rounded-lg p-2">
      @if ($user->image)
        <img src="{{ asset('storage/' . $user->image) }}" alt="Profil" class="w-20 h-20 rounded-full mt-2 border border-green-500 object-cover">
      @endif
    </div>

    <div class="flex justify-end gap-3 mt-6">
      <button type="button" @click="editMode = false" class="px-4 py-2 rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">
        Batal
      </button>
      <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
        Simpan
      </button>
    </div>
  </form>
</div>
@endsection
